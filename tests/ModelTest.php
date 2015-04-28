<?php

use Model\Base;

class ModelTest extends PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $model = new Base(['property' => 'value']);
        $this->assertEquals('value', $model->property);
        $this->assertInstanceOf('ArrayAccess', $model);
    }

    public function testAttributes()
    {
        $model = new Base();

        $model->property = 'value1';
        $this->assertEquals('value1', $model->getAttribute('property'));

        $model['property'] = 'value2';
        $this->assertEquals('value2', $model['property']);
        $this->assertTrue(isset($model['property']));

        $attrs = $model->getAttributes();
        $this->assertEquals(['property' => 'value2', 'attr' => null, 'other' => null], $attrs);

        $i = 0;
        foreach ($model as $property => $value) {
            $i++;
        }
        $this->assertEquals(3, $i);
    }

    public function testSettersAndGetters()
    {
        $model = new Base();

        $this->assertFalse(isset($model->undefinedProperty));
        $this->assertEquals(0, $model->virtualProperty);

        $model->virtualProperty = 123;
        $this->assertEquals(123, $model->virtualProperty);
        $this->assertTrue(isset($model->virtualProperty));

        $model = new Base();
        $model->setProperty('prop1')->setAttr('prop2');
        $this->assertEquals('prop1', $model->property);
        $this->assertEquals('prop2', $model->attr);
    }

    public function testToArray()
    {
        $model = new Base();
        $model->property = 'value';

        $this->assertEquals(['property' => 'value', 'attr' => null, 'other' => null], $model->toArray());
        $this->assertEquals(['property' => 'value'], $model->toArray(['include' => 'property']));
        $this->assertEquals(['property' => 'value'], $model->toArray(['include' => ['property']]));

        $this->assertEquals(['attr' => null], $model->toArray(['include' => ['attr']]));
        $this->assertEquals(['attr' => null], $model->toArray(['exclude' => ['property', 'other']]));
    }

    public function testPopulate()
    {
        $obj = new stdClass();
        $items = [
            ['property' => 1],
            ['property' => $obj],
            ['property' => 'value2'],
        ];

        $array_of_objects = Base::populate($items, true);
        $this->assertEquals(3, count($array_of_objects));
        $this->assertInstanceOf('\Model\Base', $array_of_objects[0]);
        $this->assertEquals('value2', $array_of_objects[2]->property);

        $array_of_objects = Base::populate($items, true, 'property');
        $this->assertEquals(1, $array_of_objects[1]->property);
        $this->assertEquals($obj, $array_of_objects[spl_object_hash($obj)]->property);
        $this->assertEquals('value2', $array_of_objects['value2']->property);

        $resultSet = Base::populate($items);
        $this->assertInstanceOf('\Reach\ResultSet', $resultSet);
        $this->assertEquals(3, count($resultSet));
    }

    public function testAddError()
    {
        $model = new Base();
        $model->addError('error');
        $this->assertEquals(['error'], PHPUnit_Framework_Assert::readAttribute($model, '_errors'));
    }

    public function testAddErrors()
    {
        $model = new Base();
        $expected = [['error1'], ['error2']];
        $model->addErrors($expected);
        $this->assertEquals($expected, PHPUnit_Framework_Assert::readAttribute($model, '_errors'));
    }

    public function testGetErrors()
    {
        $model = new Base();
        $expected = [['error1'], ['error2']];
        $model->addErrors($expected);
        $this->assertEquals($expected, $model->getErrors('object'));
    }

    public function testClearErrors()
    {
        $model = new Base();
        $expected = [['error1'], ['error2']];
        $model->addErrors($expected);
        $model->clearErrors();
        $this->assertEquals([], PHPUnit_Framework_Assert::readAttribute($model, '_errors'));
    }

    public function testArrayDiffMulti()
    {
        $a1 = ['fld1' => 1, 'fld2' => 2, 'fld4' => ['fld41' => 1, 'fld43' => 43]];
        $a2 = ['fld1' => 1, 'fld3' => [], 'fld4' => ['fld41' => 1, 'fld42' => 42]];

        $expected = [
            'fld2' => 2,
            'fld4' => ['fld42' => 42, 'fld43' => 43],
            'fld3' => [],
        ];
        $this->assertEquals($expected, Base::arrayDiffMulti($a1, $a2));
    }
}
 