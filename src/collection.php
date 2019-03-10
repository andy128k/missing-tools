<?php

namespace PFF;

final class Collection
{
    /*
     * Example output:
     *    1   4   7   9
     *    2   5   8  10
     *    3   6
     */
    public static function columns($collection, $columnsCount)
    {
        $count = count($collection);
        $r = $count % $columnsCount;
        $length = ($count - $r) / $columnsCount;

        $columnLengths = array();
        for ($i = 0; $i < $columnsCount; ++$i) {
            $columnLengths[] = $length + ($i < $r ? 1 : 0);
        }

        $column = array();
        $columnIndex = 0;
        foreach ($collection as $item) {
            if (count($column) >= $columnLengths[$columnIndex]) {
                $columns[] = $column;
                $column = array();
                $columnIndex++;
            }
            $column[] = $item;
        }
        $columns[] = $column;
        return $columns;
    }

    public static function chunks($collection, $size, $pad=false)
    {
        $result = array();
        $chunk = array();
        foreach ($collection as $item) {
            $chunk[] = $item;
            if (count($chunk) === $size) {
                $result[] = $chunk;
                $chunk = array();
            }
        }
        $c = count($chunk);
        if ($c != 0) {
            if ($pad) {
                while ($c < $size) {
                    $chunk[] = null;
                    ++$c;
                }
            }
            $result[] = $chunk;
        }
        return $result;
    }

    public static function groupBy($collection, $key)
    {
        $groups = array();
        foreach ($collection as $item)
            Arr::pushToKey($groups, call_user_func($key, $item), $item);
        return $groups;
    }

    public static function groupByProperty($collection, $propertyName)
    {
        $groups = array();
        foreach ($collection as $item) {
            $key = $item->$propertyName;
            Arr::pushToKey($groups, $key, $item);
        }
        return $groups;
    }

    public static function groupByMethod(/*$collection, $methodName, ...$args*/)
    {
        $args = func_get_args();
        $collection = $args[0];
        $methodName = $args[1];
        $methodArgs = array_slice($args, 2);

        $groups = array();
        foreach ($collection as $item) {
            $key = call_user_func_array(array($item, $methodName), $methodArgs);
            Arr::pushToKey($groups, $key, $item);
        }
        return $groups;
    }

    /**
     * indexByProperty($collection, $propertyName) === array_column($collection, null, $propertyName) for PHP >= 7
     */
    public static function indexByProperty($collection, $propertyName)
    {
        $groups = array();
        foreach ($collection as $item) {
            $key = is_object($item) ? $item->$propertyName : $item[$propertyName];
            $groups[$key] = $item;
        }
        return $groups;
    }

    /**
     * columnByProperty($collection, $value, $key) === array_column($collection, $value, $key) for PHP >= 7
     */
    public static function columnByProperty($collection, $valueProperty, $keyProperty)
    {
        $groups = array();
        foreach ($collection as $item) {
            $key = is_object($item) ? $item->$keyProperty : $item[$keyProperty];
            $value = is_object($item) ? $item->$valueProperty : $item[$valueProperty];
            $groups[$key] = $value;
        }
        return $groups;
    }

    public static function inits($collection)
    {
        $result = array();
        $init = array();

        $result[] = $init;
        foreach ($collection as $item) {
            $init = array_merge($init, array($item));
            $result[] = $init;
        }

        return $result;
    }
}

