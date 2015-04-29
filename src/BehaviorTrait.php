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
                $this->attachBehavior($name, $behavior);
            }
        }

        return $this;
    }

    /**
     * @param string $name
     * @param array $behavior_options
     * @return object
     * @throws \Exception
     */
    public function attachBehavior($name, $behavior_options)
    {
        if (!($behavior_options instanceof Behavior)) {
            if (empty($behavior_options['behaviorName'])) {
                $behavior_options['behavior_name'] = $name;
            }
            $behavior_options = self::createObject($behavior_options);
        }

        if (is_int($name)) {
            $behavior_options->attach($this);
            $this->_behaviors[] = $behavior_options;
        } else {
            if (isset($this->_behaviors[$name])) {
                $this->_behaviors[$name]->detach();
            }
            $behavior_options->attach($this);
            $this->_behaviors[$name] = $behavior_options;
        }

        return $this;
    }
}
