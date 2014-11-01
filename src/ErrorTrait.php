<?php

namespace Reach;

trait ErrorTrait
{

    private $_errors = [];


    public function addError($message, $code = 0)
    {
        $this->_errors[$code][] = $message;
    }

    /**
     * @param array|\Iterator $errors
     */
    public function addErrors($errors)
    {
        foreach ($errors as $code => $message) {
            if (is_array($message)) {
                foreach ($message as $msg) {
                    $this->_errors[$code][] = $msg;
                }
            } else {
                $this->_errors[$code][] = $message;
            }
        }
    }

    public function hasErrors($code = null)
    {
        if ($code === null) {
            return $this->_errors !== [];
        } else {
            return isset($this->_errors[$code]);
        }
    }

    public function getError($code)
    {
        return isset($this->_errors[$code]) ? reset($this->_errors[$code]) : null;
    }

    public function getErrors($code = null)
    {
        if ($code === null) {
            return $this->_errors;
        } else {
            return isset($this->_errors[$code]) ? $this->_errors[$code] : [];
        }
    }

    public function clearErrors($code = null)
    {
        if ($code === null) {
            $this->_errors = [];
        } else if (isset($this->_errors[$code])) {
            unset($this->_errors[$code]);
        }
    }

}
