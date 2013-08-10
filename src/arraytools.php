<?php

final class ArrayTools
{
    public static function get($array, $key, $default=null)
    {
        if (array_key_exists($key, $array))
            return $array[$key];
        else
            return $default;
    }

    public static function ensureKeyExists(&$array, $key, $default=null)
    {
        if (!array_key_exists($key, $array))
            return $array[$key] = $default;
    }

    public static function pushToKey(&$array, $key, $value)
    {
        if (array_key_exists($key, $array))
            return $array[$key][] = $value;
        else
            return $array[$key] = array($value);
    }

    public static function pop(&$array, $key, $default=null)
    {
        if (array_key_exists($key, $array)) {
            $value = $array[$key];
            unset($array[$key]);
            return $value;
        } else {
            return $default;
        }
    }
}

