<?php

namespace Reach;

trait ErrorTrait
{

    private $_errors = [];


    /**
     * @param mixed $message
     * @return $this
     */
    public function addError($message)
    {
        $this->_errors[] = $message;
        return $this;
    }

    /**
     * @param array|\Iterator $errors
     */
    public function addErrors($errors)
    {
        $this->_errors = $errors;
    }

    /**
     * @param $code
     * @return bool
     */
    public function hasErrors($code = null)
    {
        return false;
    }

    public function getError($code)
    {
        return null;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function clearErrors()
    {
        $this->_errors = [];
    }

}
