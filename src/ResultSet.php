<?php

namespace Reach;

use ArrayObject;

class ResultSet extends ArrayObject implements ResultSetInterface
{

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this);
    }

    /**
     * @param string $property
     * @throws \Exception
     * @return array
     */
    public function pluck($property)
    {
        $results = [];

        reset($this);
        while (list($key, $item) = each($this)) {
            if (is_array($item) && isset($item[$property])) {
                $results[] = $item[$property];
            } else if (is_object($item) && isset($item->$property)) {
                $results[] = $item->$property;
            } else {
                throw new \Exception('Property is not found');
            }
        }

        return $results;
    }

    /**
     * @param callable $fn ($item, $key)
     * @return mixed|null
     */
    public function find($fn)
    {
        reset($this);
        while (list($key, $item) = each($this)) {
            if (call_user_func_array($fn, [$item, $key])) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param callable $fn ($item, $key)
     * @return ResultSet
     */
    public function filter($fn)
    {
        $resultSet = new self();
        reset($this);
        while (list($key, $item) = each($this)) {
            if (call_user_func_array($fn, [$item, $key])) {
                $resultSet->append($item);
            }
        }

        return $resultSet;
    }

    /**
     * @param ResultSetInterface $resultSet
     * @return $this
     */
    public function mergeWith(ResultSetInterface $resultSet)
    {
        $resultSet->rewind();
        while ($item = $resultSet->current()) {
            $this->append($item);
            $resultSet->next();
        }

        return $this;
    }

    /**
     * @param callable $fn ($item, int $i)
     * @return ResultSet
     */
    public function map($fn)
    {
        $i = 0;
        $resultSet = new self();
        foreach ($this as $key => $item) {
            $resultSet->append(call_user_func_array($fn, [$item, $i++]));
        }

        return $resultSet;
    }

    public function asArray()
    {
        $array = [];
        foreach ($this as $key => $item) {
            $array[$key] = get_object_vars($item);
        }

        return $array;
    }

    /**
     * @param array $params
     * @return string
     */
    public function toJson(array $params = [])
    {
        if (method_exists($this, 'toArray')) {
            return json_encode($this->toArray($params));
        } else {
            return json_encode($this);
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public function toArray(array $params = [])
    {
        $result = [];
        foreach ($this as $key => $item) {
            /** @var $item Model */
            if (is_array($item)) {
                $result[$key] = $item;
            } else if (is_object($item)) {
                if (method_exists($item, 'toArray')) {
                    $result[$key] = $item->toArray($params);
                } else {
                    $result[$key] = (array)$item;
                }
            } else {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this);
    }

//    public function hasNext()
//    {
//
//    }

    /**
     * @return void
     */
    public function rewind()
    {
        reset($this);
    }
}
