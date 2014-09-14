<?php

namespace PFF;

class MultidimensionalArray implements \ArrayAccess
{
    private $keys = array();
    private $storage = array();

    function count()
    {
        return count($this->keys);
    }

    function contains()
    {
        return $this->offsetExists(func_get_args());
    }

    function offsetExists($key)
    {
        $key = array_values($key);
        return false !== array_search($key, $this->keys);
    }

    function get()
    {
        return $this->offsetGet(func_get_args());
    }

    function offsetGet($key)
    {
        $key = array_values($key);
        $index = array_search($key, $this->keys);
        if ($index !== false)
            return $this->storage[$index];
        else
            return null;
    }

    function set()
    {
        $key = func_get_args();
        $value = array_pop($key);
        $this->offsetSet($key, $value);
        return $this;
    }

    function offsetSet($key, $value)
    {
        $key = array_values($key);
        $index = array_search($key, $this->keys);
        if ($index === false) {
            $this->keys[] = $key;
            end($this->keys);
            $index = key($this->keys);
        }
        $this->storage[$index] = $value;
    }

    function reset()
    {
        $this->offsetUnset(func_get_args());
        return $this;
    }

    function offsetUnset($key)
    {
        $key = array_values($key);
        $index = array_search($key, $this->keys);
        if ($index !== false) {
            unset($this->keys[$index]);
            unset($this->storage[$index]);
        }
    }

    function __debugInfo()
    {
        $i = array();
        foreach ($this->keys as $index => $key) {
            $i[implode(', ', $key)] = $this->storage[$index];
        }
        return $i;
    }

    public function ensureKeyExists($key, $default=null)
    {
        $key = array_values($key);
        $index = array_search($key, $this->keys);
        if ($index === false) {
            $this->keys[] = $key;
            end($this->keys);
            $index = key($this->keys);
            $this->storage[$index] = $default;
        }
        return $this;
    }

    public function pushToKey($key, $value)
    {
        $key = array_values($key);
        $index = array_search($key, $this->keys);
        if ($index === false) {
            $this->keys[] = $key;
            end($this->keys);
            $index = key($this->keys);

            $this->storage[$index] = array($value);
        } else {
            $this->storage[$index][] = $value;
        }
        return $this;
    }
}

