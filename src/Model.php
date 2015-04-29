<?php

namespace Reach;

use ArrayAccess;
use ArrayIterator;
use Iterator;
use IteratorAggregate;
use ReflectionClass;
use ReflectionProperty;
use Traversable;

abstract class Model implements IteratorAggregate, ArrayAccess
{

    use EventableTrait;
    use BehaviorTrait;
    use ErrorTrait;

    public static $_attributes = [];


    public function __construct($attributes = null)
    {
        $this->ensureBehaviors();
        $this->beforeConstruct($attributes);
        if ($attributes && is_array($attributes)) {
            $this->setAttributes($attributes);
        }
        $this->afterConstruct($attributes);

        $this->init();
    }

    public function setAttributes($values)
    {
        if (!$values instanceof Traversable && !is_object($values) && !is_array($values)) {
            return;
        }

        foreach ($values as $name => $value) {
            if ($this->hasAttribute($name)) {
                $this->setAttribute($name, $value);
            }
        }
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function hasAttribute($attribute)
    {
        return array_key_exists($attribute, $this->attributes());
    }

    public function attributes()
    {
        $model_class = get_called_class();
        if (empty(self::$_attributes[$model_class])) {
            $attributes = [];

            $reflect = new ReflectionClass($this);
            $props = array_filter(
                $reflect->getProperties(ReflectionProperty::IS_PUBLIC),
                function (ReflectionProperty $prop) {
                    return !$prop->isStatic();
                }
            );

            foreach ($props as $prop) {
                $class = '';
                if ($dc = $prop->getDocComment()) {
                    preg_match('#@var\s(.*)\s#', $dc, $m);
                    $class = $m[1];
                }
                $attributes[$prop->name] = $class;
            }

            self::$_attributes[$model_class] = $attributes;
        }

        return self::$_attributes[$model_class];
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
            return $this;
        } else if ($this->hasAttribute($name)) {
            $this->$name = $value;
            return $this;
        }

        return $this;
    }

    public function init()
    {
        $this->trigger('init', new Event($this));
    }

    public function beforeConstruct($attributes)
    {
        $event = new Event($this);
        $event->attributes = $attributes;
        $this->trigger('beforeConstruct', $event);
    }

    public function afterConstruct($attributes)
    {
        $event = new Event($this);
        $event->attributes = $attributes;
        $this->trigger('afterConstruct', $event);
    }

//	public function __call($name, $parameters) {
//		if (method_exists($this, $name)) {
//			return call_user_func_array(array($this, $name), $parameters);
//		}
//
//		if ($this->$name instanceof Closure) {
//			return call_user_func_array($this->$name, $parameters);
//		}
//
//		error_log(get_class($this) . ' and its behaviors do not have a method or closure named "' . $name . '".');
//		return null;
//	}

    /**
     * @param mixed       $items
     * @param bool        $as_array
     * @param string|null $index
     * @return Model[]|ResultSet of Model
     */
    public static function populate($items, $as_array = false, $index = null)
    {
        $objects = $as_array ? [] : new ResultSet();
        foreach ($items as $item) {
            if (($object = self::instantiate($item)) !== null) {
                if ($as_array) {
                    if (null === $index) {
                        $objects[] = $object;
                    } else if (isset($object->$index)) {
                        $val = $object->$index;
                        $key = is_scalar($val) ? $object->$index : spl_object_hash($object->$index);
                        $objects[$key] = $object;
                    }
                } else {
                    $objects->append($object);
                }
            }
        }

        return $objects;
    }

    /**
     * @static
     * @param $model_data
     * @return Model|null
     */
    public static function instantiate($model_data = null)
    {
        $className = get_called_class();
        /** @var Model $model */
        $model = new $className($model_data);
        return $model;
    }

    /**
     * @param array $expected
     * @param array $base
     * @return array|bool
     */
    public static function arrayDiffMulti(array $expected, array $base)
    {
        $diff = [];

        // get unique arrays keys
        $keys = array_unique(array_merge(array_keys($expected), array_keys($base)));

        foreach ($keys as $key) {
            // key checking
            if (!array_key_exists($key, $expected)) {
                $diff[$key] = $base[$key];
                continue;
            }

            $val = $expected[$key];

            // key checking
            if (!array_key_exists($key, $base)) {
                $diff[$key] = $val;
                continue;
            }

            // values checking
            if (!is_array($val) || !is_array($base[$key])) {
                if ($base[$key] == $val) {
                    continue;
                } else {
                    $diff[$key] = $val;
                    continue;
                }
            }

            // arrays checking
            $res = self::arrayDiffMulti($base[$key], $val);
            if ($res !== []) {
                $diff[$key] = $res;
            }
        }

        return $diff;
    }

    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    public function __set($name, $value)
    {
        return $this->setAttribute($name, $value);
    }

    /**
     * @param $method
     * @param $arguments
     * @return Model
     */
    public function __call($method, $arguments)
    {
        $prefix = strtolower(substr($method, 0, 3));
        $attribute = strtolower(preg_replace('/^set|get/i', '', $method));
        if ($prefix === 'set') {
            if (property_exists(get_called_class(), $attribute)) {
                $this->setAttribute($attribute, $arguments[0]);
            }
            return $this;
        } else if ($prefix === 'get') {
            return $this->getAttribute($attribute);
        }

        return call_user_func_array($method, $arguments);
    }

    public function getAttribute($attribute)
    {
        $getter = 'get' . $attribute;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        if (property_exists(get_class($this), $attribute)) {
            return $this->$attribute;
        }

        return null;
    }

    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        } else if (property_exists($this, $name)) {
            return isset($this->$name);
        }

        return false;
    }

    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } else if (property_exists($this, $name)) {
            $this->$name = null;
        }
    }

    public function toArray(array $params = [])
    {
        $result = [];
        $attributes = array_keys($this->attributes());
        if (!empty($params['include'])) {
            $params['include'] = is_array($params['include']) ? $params['include'] : [$params['include']];
            $attributes = array_intersect($attributes, $params['include']);
        }
        if (!empty($params['exclude'])) {
            $params['exclude'] = is_array($params['exclude']) ? $params['exclude'] : [$params['exclude']];
            $attributes = array_diff($attributes, $params['exclude']);
            if (empty($attributes)) {
                return $result;
            }
        }

        foreach ($this->getAttributes($attributes) as $key => $val) {
            $result[$key] = $val;
        }

        return $result;
    }

    public function getAttributes(array $names = null)
    {
        $values = [];
        foreach ($this->attributes() as $name => $type) {
            if ($names && array_search($name, $names) === false) {
                continue;
            }
            $values[$name] = $this->getAttribute($name);
        }

        return $values;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->getAttributes());
    }

    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $item)
    {
        $this->$offset = $item;
    }

    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
}
