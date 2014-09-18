<?php

namespace Reach;

trait BehaviorTrait
{

    use CreateObjectTrait;


    /** @var Behavior[] */
    protected $_behaviors;


    public function behaviors()
    {
        return [];
    }

    public function ensureBehaviors()
    {
        if ($this->_behaviors === null) {
            $this->_behaviors = [];
            foreach ($this->behaviors() as $name => $behavior) {
                $this->_attachBehavior($name, $behavior);
            }
        }
    }

    private function _attachBehavior($name, $behavior)
    {
        if (!($behavior instanceof Behavior)) {
            if (empty($behavior['behaviorName'])) {
                $behavior['behavior_name'] = $name;
            }
            $behavior = self::createObject($behavior);
        }

        if (is_int($name)) {
            $behavior->attach($this);
            $this->_behaviors[] = $behavior;
        } else {
            if (isset($this->_behaviors[$name])) {
                $this->_behaviors[$name]->detach();
            }
            $behavior->attach($this);
            $this->_behaviors[$name] = $behavior;
        }

        return $behavior;
    }
}
