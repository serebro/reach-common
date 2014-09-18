<?php

use Model\Base;
use Reach\ResultSet;

class ResultSetTest extends PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $resultSet = new ResultSet();
        $this->assertInstanceOf('\ArrayObject', $resultSet);
        $this->assertInstanceOf('\Reach\ResultSetInterface', $resultSet);
    }

    public function testArrayObject()
    {
        $resultSet = new ResultSet(
            [
                ['key' => 'value1'],
                ['key' => 'value2'],
            ]
        );

        $this->assertTrue(is_array($resultSet[0]));
        $this->assertEquals(['key' => 'value2'], $resultSet[1]);
    }

    public function testPluck()
    {
        $resultSet = new ResultSet(
            [
                ['key' => 'value1'],
                ['key' => 'value2'],
            ]
        );
        $this->assertEquals(['value1', 'value2'], $resultSet->pluck('key'));

        $resultSet = new ResultSet(
            [
                new Base(['property' => 'value1']),
                new Base(['property' => 'value2']),
            ]
        );
        $this->assertEquals(['value1', 'value2'], $resultSet->pluck('property'));
    }

    public function testFind()
    {
        $resultSet = new ResultSet(
            [
                ['key' => 'value1'],
                ['key' => 'value2'],
            ]
        );

        $element = $resultSet->find(
            function ($item) {
                return $item['key'] === 'value2';
            }
        );

        $this->assertEquals(['key' => 'value2'], $element);
    }

    public function testFirst()
    {
        $resultSet = new ResultSet(
            [
                ['key' => 'value1'],
                ['key' => 'value2'],
            ]
        );

        $this->assertEquals(['key' => 'value1'], $resultSet->first());
    }

    public function testFilter()
    {
        $resultSet = new ResultSet(
            [
                ['key' => 1],
                ['key' => 2],
                ['key' => 3],
            ]
        );

        $results = $resultSet->filter(
            function ($item) {
                return $item['key'] > 1;
            }
        );

        $this->assertEquals(3, count($resultSet));
        $this->assertEquals(3, $resultSet[2]['key']);

        $this->assertInstanceOf('\Reach\ResultSet', $results);
        $this->assertEquals(2, count($results));
        $this->assertEquals(2, $results[0]['key']);
        $this->assertEquals(3, $results[1]['key']);
    }

    public function testMergeWith()
    {
        $resultSet1 = new ResultSet([['key' => 'val0']]);
        $resultSet2 = new ResultSet([['key' => 'val1']]);
        $result = $resultSet1->mergeWith($resultSet2);
        $this->assertInstanceOf('\Reach\ResultSet', $result);
        $this->assertEquals(2, $result->count());
        $this->assertEquals(['key' => 'val0'], $resultSet1[0]);
        $this->assertEquals(['key' => 'val1'], $resultSet1[1]);
    }

    public function testToArray()
    {
        $obj = new stdClass();
        $obj->property = 'value3';
        $resultSet1 = new ResultSet(
            [
                ['key' => 'value0'],
                new \Model\Base(['property' => 'value1']),
                'value2',
                $obj,
            ]
        );
        $array = $resultSet1->toArray();
        $expected = [
            ['key' => 'value0'],
            ['property' => 'value1', 'attr' => null, 'other' => null],
            'value2',
            ['property' => 'value3']
        ];
        $this->assertEquals($expected, $array);
    }

    public function testToJson()
    {
        $resultSet = new ResultSet(
            [
                ['key' => 1],
                ['key' => 2],
                ['key' => 3],
            ]
        );
        $json = $resultSet->toJson();
        $expected = '[{"key":1},{"key":2},{"key":3}]';
        $this->assertEquals($expected, $json);
    }
}
 