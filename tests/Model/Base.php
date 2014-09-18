<?php

namespace Model;

use Reach\Model;

/**
 * Class Base
 * @property int virtualProperty
 * @package Model
 */
class Base extends Model
{

    public $property;

    public $attr;

    public $other;

    /** @var  int */
    private $_virtual_property = 0;


    public function setVirtualProperty($value)
    {
        $this->_virtual_property = $value;
    }

    public function getVirtualProperty()
    {
        return $this->_virtual_property;
    }

    public function setOther($value)
    {
        $this->other = '123';
        return $this;
    }
}