<?php

namespace Reach\Service;

use Reach\SingletonTrait;

class Container
{
    use SingletonTrait;

    protected static $services = [];


    public static function set($name, $service)
    {
        self::$services[$name] = $service;
    }

    public static function has($name)
    {
        return array_key_exists($name, self::$services);
    }

    public static function get($name)
    {
        return self::$services[$name];
    }
}
