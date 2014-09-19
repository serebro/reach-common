<?php


class ContainerTest extends PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $config = [
            'class' => '\Reach\Connection\Dummy',
            'host'  => 'localhost'
        ];
        \Reach\Service\Container::register('connection', $config);
        $connection = \Reach\Service\Container::get('connection');
        $this->assertInstanceOf('\Reach\Connection\Dummy', $connection);

        $connection = new \Reach\Connection\Dummy($config);
        \Reach\Service\Container::set('connection', $connection);
        $this->assertInstanceOf('\Reach\Connection\Dummy', \Reach\Service\Container::get('connection'));
        $this->assertTrue(\Reach\Service\Container::has('connection'));
    }
}