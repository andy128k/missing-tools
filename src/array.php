<?php

namespace PFF;

use \PFF\Symbol as S;

final class Arr implements \ArrayAccess
{
    private $arr;

    public function __construct($arr)
    {
        $this->arr = $arr;
    }

    public static function create($arr)
    {
        return new self($arr);
    }

    public function arr()
    {
        return $this->arr;
    }

    public function map(/* $func, ... $args */)
    {
        $args = func_get_args();
        $func = array_unshift($args);
        $pos = array_search(S::_(), $args, true);
        if (!$pos)
            $pos = 0;

        $result = array();
        foreach ($this->arr as $item) {
            $args[$pos] = $item;
            $result[] = call_user_func_array($func, $args);
        }

        return new self($result);
    }

    public function pluck($key)
    {
        $result = array();
        foreach ($this->arr as $item)
            $result[] = is_object($item) ? $item->$key : $item[$key];
        return new self($result);
    }

    /* strings */

    public function join($separator)
    {
        return implode($separator, $this->arr);
    }

    /* ArrayAccess */

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
            $this->arr[] = $value;
        else
            $this->arr[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->arr[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->arr[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->arr[$offset]) ? $this->arr[$offset] : null;
    }

    /* */

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

    public static function flatten($array)
    {
        $result = array();
        array_walk_recursive($array, function ($value) use (&$result) {
            $result[] = $value;
        });
        return $result;
    }
}

