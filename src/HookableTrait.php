<?php

namespace Reach;

trait HookableTrait {

    /**
     * @return bool
     */
    public function beforeInsert()
    {
        $event = new Event($this);
        $this->trigger('beforeInsert', $event);
        return $event->is_valid;
    }

    public function afterInsert()
    {
        $this->trigger('afterInsert', new Event($this));
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        $event = new Event($this);
        $this->trigger('beforeSave', $event);
        return $event->is_valid;
    }

    public function afterSave()
    {
        $this->trigger('afterSave', new Event($this));
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $event = new Event($this);
        $this->trigger('beforeDelete', $event);
        return $event->is_valid;
    }

    public function afterDelete()
    {
        $this->trigger('afterDelete', new Event($this));
    }

    /**
     * @return bool
     */
    public function beforeUpdate()
    {
        $event = new Event($this);
        $this->trigger('beforeUpdate', $event);
        return $event->is_valid;
    }

    public function afterUpdate()
    {
        $this->trigger('afterUpdate', new Event($this));
    }

    public function afterFind($document)
    {
        $event = new Event($this);
        $event->document = $document;
        $this->trigger('afterFind', $event);
    }
}
