<?php

namespace PFF;

final class Arr
{
    /**
     * @param array $array
     * @param string|integer $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(array $array, $key, $default=null)
    {
        if (array_key_exists($key, $array))
            return $array[$key];
        else
            return $default;
    }

    /**
     * @param array $array
     * @param string|integer $key
     * @param mixed $default
     */
    public static function ensureKeyExists(array &$array, $key, $default=null)
    {
        if (!array_key_exists($key, $array))
            $array[$key] = $default;
    }

    /**
     * @param array $array
     * @param string|integer $key
     * @param mixed $value
     */
    public static function pushToKey(array &$array, $key, $value)
    {
        if (array_key_exists($key, $array))
            $array[$key][] = $value;
        else
            $array[$key] = array($value);
    }

    /**
     * @param array $array
     * @param string|integer $key
     * @param mixed $default
     * @return mixed
     */
    public static function pop(array &$array, $key, $default=null)
    {
        if (array_key_exists($key, $array)) {
            $value = $array[$key];
            unset($array[$key]);
            return $value;
        } else {
            return $default;
        }
    }

    /**
     * @param array $array
     * @return array
     */
    public static function flatten(array $array)
    {
        $result = array();
        array_walk_recursive($array, function ($value) use (&$result) {
            $result[] = $value;
        });
        return $result;
    }

    /**
     * @param array $array
     * @param string|integer $key
     * @return array
     */
    public static function pluck(array $array, $key)
    {
        $result = array();
        foreach ($array as $item)
            $result[] = is_object($item) ? $item->$key : $item[$key];
        return $result;
    }
}
