<?php

namespace Reach\Service;

use InvalidArgumentException;
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

    /**
     * @param string $service_name
     * @param array  $config
     * @param string $class
     */
    public static function register($service_name, $config, $class = null)
    {
        if (!is_string($service_name) && !is_array($config)) {
            throw new InvalidArgumentException('Invalid argument');
        }

        if (!isset($config['class']) && !$class) {
            throw new InvalidArgumentException('Invalid argument');
        }

        if ($class) {
            $config['class'] = $class;
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
