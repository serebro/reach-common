<?php

namespace Reach;

abstract class Cache
{

    use EventableTrait;

    /** @var int seconds */
    public $ttl = 60;


    /**
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        $key = $this->hashKey($key);
        return $this->storageExists($key);
    }

    /**
     * @param $key
     * @return string
     */
    public function hashKey($key)
    {
        if (!is_string($key)) {
            $key = serialize($key);
        }

        return md5($key);
    }

    /**
     * @param $hashedKey
     * @return bool
     */
    abstract protected function storageExists($hashedKey);

    /**
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        $key = $this->hashKey($key);
        return $this->storageDelete($key);
    }

    /**
     * @param $hashedKey
     * @return bool
     */
    abstract protected function storageDelete($hashedKey);

    /**
     * @param $key
     * @return null|mixed
     */
    public function get($key)
    {
        $key = $this->hashKey($key);
        $value = $this->storageGet($key);
        if ($value) {
            return unserialize($value);
        }

        return null;
    }

    /**
     * @param $hashedKey
     * @return mixed
     */
    abstract protected function storageGet($hashedKey);

    /**
     * @param     $key
     * @param     $value
     * @param int $duration
     * @return bool
     */
    public function set($key, $value, $duration = null)
    {
        $key = $this->hashKey($key);
        $value = serialize($value);
        return $this->storageSet($key, $value, $duration);
    }

    /**
     * @param     $hashedKey
     * @param     $value
     * @param int $duration
     * @return bool
     */
    abstract protected function storageSet($hashedKey, $value, $duration);
}
