<?php

namespace Reach;

interface ResultSetInterface
{

    /**
     * @param string $attribute - One level deep "field.sub_field"
     * @return array
     */
    public function pluck($attribute);

    /**
     * @param $fn - function for condition
     * @return Model
     */
    public function find($fn);

    public function first();

    public function filter($fn);

    public function asArray();

    public function map($fn);

    public function current();

    public function next();

    public function rewind();

    /**
     * @param ResultSetInterface $resultSet
     * @return $this
     */
    public function mergeWith(ResultSetInterface $resultSet);

    /**
     * @param array $params
     * @return array
     */
    public function toArray(array $params = []);

    /**
     * @param array $params
     * @return string
     */
    public function toJson(array $params = []);
}
