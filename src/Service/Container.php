<?php

namespace Reach\Service;

use InvalidArgumentException;
use Reach\DI\AdapterInterface;
use Reach\DI\DefaultAdapter;
use Reach\SingletonTrait;

class Container
{

	use SingletonTrait;

	/** @var  AdapterInterface */
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
	public static function setDI(AdapterInterface $di)
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
			throw new \Exception();
		}

		return self::$di->getInstance($name);
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
			throw new \Exception();
		}

		self::$di->register($service_name, $config, $class);
	}

}
