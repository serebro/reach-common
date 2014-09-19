<?php

namespace Reach\Service;

use Reach\SingletonTrait;

class Container
{
    use SingletonTrait;

    protected static $services = [];

    protected static $configs = [];

    public static function set($name, $service)
    {
        self::$services[$name] = $service;
    }

    public static function has($name)
    {
        return array_key_exists($name, self::$services) || array_key_exists($name, self::$configs);
    }

    public static function get($name)
    {
        if (!isset(self::$services[$name])) {
            self::$services[$name] = self::resolve($name);
        }

        return self::$services[$name];
    }

    public static function register($service_name, $config)
    {
        if (!is_string($service_name) && !is_array($config) && !isset($config['class'])) {
            throw new \InvalidArgumentException('Invalid argument');
        }

        self::$configs[$service_name] = $config;
    }

    public static function resolve($name)
    {
        $config = self::$configs[$name];
        $class = $config['class'];
        unset($config['class']);
        return new $class($config);
    }
}
