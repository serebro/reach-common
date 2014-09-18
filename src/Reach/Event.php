<?php

namespace Reach;

class Event
{

    public $model;

    /** @var bool */
    public $is_valid = true;


    public function __construct($model)
    {
        $this->model = $model;
    }
}