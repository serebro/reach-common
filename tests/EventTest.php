<?php

use Model\Base as Model;

class EventTest extends PHPUnit_Framework_TestCase
{

    public function testOnOffTrigger()
    {
        $obj = new Model();

        $obj->on(
            'testClosure',
            function ($param = '') {
                echo 'eventTestClosure' . $param;
            }
        );
        $obj->trigger('testClosure', '123');
        $expectedString = 'eventTestClosure123';
        $this->expectOutputString($expectedString);

        $obj->on('testStatic', '\Foo::testStatic');
        $obj->on('testStatic', ['\Foo', 'testStatic']);
        $obj->trigger('testStatic');
        $expectedString .= 'TestStaticTestStatic';
        $this->expectOutputString($expectedString);

        $foo = new Foo();
        $obj->on('testDynamic', [$foo, 'testDynamic']);
        $obj->trigger('testDynamic');
        $expectedString .= 'TestDynamic';
        $this->expectOutputString($expectedString);

        $obj->off('testStatic');
        $obj->trigger('testStatic');
        $this->expectOutputString($expectedString);
    }

    public function testException()
    {
        $obj = new Model();
        try {
            $obj->on('test', 123);
        } catch(\Exception $e) {
            return;
        }

        $this->fail('!!!');
    }
}


class Foo
{

    public static function testStatic()
    {
        echo 'TestStatic';
    }

    public static function testDynamic()
    {
        echo 'TestDynamic';
    }
}