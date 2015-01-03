<?php

namespace Reach\DI;

use Exception;
use InvalidArgumentException;

class DefaultAdapter implements AdapterInterface
{

	private $services = [];

	private $configs = [];


	public function set($name, $service)
	{
		$this->services[$name] = $service;
	}

	public function has($name)
	{
		return array_key_exists($name, $this->services) || array_key_exists($name, $this->configs);
	}

	public function get($name)
	{
		if (!isset($this->services[$name])) {
			$this->services[$name] = self::resolve($name);
		}

		return $this->services[$name];
	}

	/**
	 * @param string $service_name
	 * @param array  $config
	 * @param string $class
	 */
	public function register($service_name, $config, $class = null)
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

		$this->configs[$service_name] = $config;
	}

	protected function resolve($name)
	{
        if (empty($this->configs[$name])) {
            throw new Exception("Configuration for \"$name\" is not defined");
        }

        $config = $this->configs[$name];

        if (empty($config['class'])) {
            throw new Exception("The parameter \"class\" is not defined");
        }

        $class = $config['class'];
        if (!class_exists($class, false)) {
            throw new Exception("The class \"$class\" is not found");
        }

		unset($config['class']);
		return new $class($config);
	}
}
