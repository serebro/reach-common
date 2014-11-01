<?php

namespace Reach;

trait CreateObjectTrait
{

    /**
     * Creates a new object using the given configuration.
     * @param array $params the constructor parameters
     * @return object the created object
     * @throws \Exception if the configuration is invalid.
     */
    public static function createObject($type, array $params = [])
    {
        if (is_callable($type, true)) {
            return call_user_func($type, $params);
        }

        if (is_string($type) && class_exists($type)) {
            $object = new $type();
        } elseif (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            unset($type['class']);
            $reflection = new \ReflectionClass($class);
            $object = $reflection->newInstanceArgs($params);
        }

        if (!empty($object) && is_array($type)) {
            foreach ($type as $property => $value) {
                $object->$property = $value;
            }
            return $object;
        }

        throw new \Exception("Unsupported configuration type: " . gettype($type));
    }
}
