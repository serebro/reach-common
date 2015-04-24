<?php

namespace Reach\Service;

use InvalidArgumentException;
use Reach\DI\AdapterInterface;
use Reach\DI\DefaultAdapter;
use Reach\SingletonTrait;

class Container
{

	use SingletonTrait;

	private static $di;


	/**
	 * @return AdapterInterface
	 */
	public static function getDI()
	{
		return self::$di;
	}

	/**
	 * @param AdapterInterface $di
	 */
	public static function setDI($di)
	{
		self::$di = $di;
	}

	/**
	 * @deprecated
	 * @param $name
	 * @param $service
	 */
	public static function set($name, $service)
	{
		self::$di->set($name, $service);
	}

	/**
	 * @deprecated
	 * @param $name
	 * @return mixed
	 * @throws \Exception
	 */
	public static function get($name)
	{
		if (!self::$di instanceof DefaultAdapter) {
			throw new \Exception('It is not Default Adapter');
		}

		return self::$di->get($name);
	}

	public static function has($name)
	{
		return self::$di->has($name);
	}

	/**
	 * @deprecated
	 * @param string $service_name
	 * @param array  $config
	 * @param string $class
	 * @throws \Exception
	 */
	public static function register($service_name, $config, $class = null)
	{
		if (!self::$di) {
			self::setDI(new DefaultAdapter());
		}

		if (!self::$di instanceof DefaultAdapter) {
			throw new \Exception('It is not Default Adapter');
		}

		self::$di->register($service_name, $config, $class);
	}

}
