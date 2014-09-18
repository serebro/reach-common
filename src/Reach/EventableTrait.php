<?php

namespace Reach;

trait EventableTrait
{

    private $_listeners = [];


    /**
     * @param string $name
     * @param callable $callable
     * @return $this
     * @throws \Exception
     */
    public function on($name, $callable)
    {
        if (!is_callable($callable, true)) {
            throw new \Exception('Second parameter must be a callable');
        }
        if (!isset($this->_listeners[$name])) {
            $this->_listeners[$name] = [];
        }
        array_push($this->_listeners[$name], $callable);
        return $this;
    }

    /**
     * @param string $name
     * @param array $args
     * @return $this
     */
    public function trigger($name, $args = null)
    {
        if (!is_array($args)) {
            $args = [$args];
        }

        if (isset($this->_listeners[$name])) {
            foreach ($this->_listeners[$name] as $callable) {
                call_user_func_array($callable, $args);
            }
        }
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function off($name)
    {
        if (isset($this->_listeners[$name])) {
            unset($this->_listeners[$name]);
        }
        return $this;
    }
}
