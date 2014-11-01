<?php

namespace Reach\Connection;

use Reach\ConnectionAbstract;

class Dummy extends ConnectionAbstract {

    public function __construct(array $config)
    {
    }

    public function close()
    {
    }
}
