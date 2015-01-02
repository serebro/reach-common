<?php

namespace Reach\DI;

class Phalcon
{

	/** @var \Phalcon\DiInterface  */
	private $di;

	public function __construct(\Phalcon\DiInterface $di)
	{
		$this->di = $di;
	}

	/**
	 * @param string $name
	 * @return mixed
	 * @throws \Exception
	 */
	public function getInstance($name)
	{
		if ($this->di->has($name)) {
			return $this->di->get($name);
		}

		throw new \Exception("Service \"$name\" is not defined");
	}
}
