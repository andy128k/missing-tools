<?php

namespace PFF;

class Map
{
    private $data = array();

    function count()
    {
        return count($this->data);
    }

    function contains($key)
    {
        foreach ($this->data as &$entry) {
            if ($this->keysAreEqual($key, $entry[0])) {
                return true;
            }
        }
        return false;
    }

    function get($key)
    {
        foreach ($this->data as &$entry) {
            if ($this->keysAreEqual($key, $entry[0])) {
                return $entry[1];
            }
        }
        return null;
    }

    function set($key, $value)
    {
        foreach ($this->data as &$entry) {
            if ($this->keysAreEqual($key, $entry[0])) {
                $entry[1] = $value;
                return;
            }
        }
        $this->data[] = [$key, $value];
    }

    function remove($key)
    {
        $this->data = array_filter($this->data, function ($entry) use ($key) {
            return !$this->keysAreEqual($key, $entry[0]);
        });
    }

    function __debugInfo()
    {
        $i = [];
        foreach ($this->data as &$entry) {
            $i[print_r($entry[0], true)] = print_r($entry[1], true);
        }
        return $i;
    }

    private function keysAreEqual($key, $entry)
    {
        return $entry === $key;
    }
}
