<?php

namespace Reach;

class Bench
{

    protected $start_time = [];

    protected $end_time = [];

    protected $memory_usage = [];

    const DEFAULT_NAME = 'default';


    /**
     * Sets start microtime
     * @param string $name
     * @return void
     */
    public function start($name = self::DEFAULT_NAME)
    {
        $this->start_time[$name] = microtime(true);
    }

    /**
     * Sets end microtime
     * @param string $name
     * @return void
     */
    public function end($name = self::DEFAULT_NAME)
    {
        $this->end_time[$name] = microtime(true);
        $this->memory_usage[$name] = memory_get_usage(true);
    }


    /**
     * Returns the elapsed time, readable or not
     * @param  boolean $raw    Whether the result must be human readable
     * @param  string  $format The format to display (printf format)
     * @param string   $name
     * @return string|float
     */
    public function getTime($raw = false, $format = null, $name = self::DEFAULT_NAME)
    {
        $elapsed = $this->end_time[$name] - $this->start_time[$name];
        return $raw ? $elapsed : self::readableElapsedTime($elapsed, $format, $name);
    }


    /**
     * Returns the memory usage at the end checkpoint
     * @param  boolean $raw    Whether the result must be human readable
     * @param  string  $format The format to display (printf format)
     * @param string   $name
     * @return string|float
     */
    public function getMemoryUsage($raw = false, $format = null, $name = self::DEFAULT_NAME)
    {
        return $raw ? $this->memory_usage[$name] : self::readableSize($this->memory_usage[$name], $format);
    }

    /**
     * Returns the memory peak, readable or not
     * @param  boolean $raw    Whether the result must be human readable
     * @param  string  $format The format to display (printf format)
     * @return string|float
     */
    public function getMemoryPeak($raw = false, $format = null)
    {
        $memory = memory_get_peak_usage(true);
        return $raw ? $memory : self::readableSize($memory, $format);
    }

    /**
     * Returns a human readable memory size
     * @param   int    $size
     * @param   string $format The format to display (printf format)
     * @param   int    $round
     * @return  string
     */
    public static function readableSize($size, $format = null, $round = 3)
    {
        $mod = 1024;

        if (is_null($format)) {
            $format = '%.2f%s';
        }

        $units = explode(' ', 'B Kb Mb Gb Tb');

        for ($i = 0; $size > $mod; $i++) {
            $size /= $mod;
        }

        if (0 === $i) {
            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return sprintf($format, round($size, $round), $units[$i]);
    }

    /**
     * Returns a human readable elapsed time
     * @param  float  $microtime
     * @param  string $format The format to display (printf format)
     * @return string
     */
    public static function readableElapsedTime($microtime, $format = null, $round = 3)
    {
        if (is_null($format)) {
            $format = '%.3f%s';
        }

        if ($microtime >= 1) {
            $unit = 's';
            $time = round($microtime, $round);
        } else {
            $unit = 'ms';
            $time = round($microtime * 1000);

            $format = preg_replace('/(%.[\d]+f)/', '%d', $format);
        }

        return sprintf($format, $time, $unit);
    }

}