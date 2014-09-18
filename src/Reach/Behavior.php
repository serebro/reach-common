<?php

namespace Reach;

abstract class Behavior
{

    /** @var string */
    public $behavior_name;

    /** @var \Reach\Mongo\DocumentInterface */
    protected $owner;


    public function events()
    {
        return [];
    }

    /**
     * @param $owner
     * @return bool
     */
    public function attach($owner)
    {
        if (!method_exists($owner, 'on')) {
            return false;
        }

        $this->owner = $owner;
        foreach ($this->events() as $event => $handler) {
            $owner->on($event, is_string($handler) ? [$this, $handler] : $handler);
        }

        return true;
    }

    public function detach()
    {
        if (!method_exists($this->owner, 'off')) {
            return false;
        }

        if ($this->owner) {
            foreach ($this->events() as $event => $handler) {
                $this->owner->off($event, is_string($handler) ? [$this, $handler] : $handler);
            }
            $this->owner = null;
        }

        return true;
    }
}
