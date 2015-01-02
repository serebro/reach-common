<?php

namespace Reach\DI;

interface AdapterInterface
{

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function get($name);

}