<?php

class ManagerTest extends PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $config = ['host' => 'localhost'];
        $connection = new \Reach\Connection\Dummy($config);
        \Reach\Service\Container::set('connection', $connection);
        $this->assertInstanceOf('\Reach\Connection\Dummy', \Reach\Service\Container::get('connection'));
        $this->assertTrue(\Reach\Service\Container::has('connection'));
    }
}