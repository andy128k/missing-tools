<?php

final class Collections
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
            ArrayTools::pushToKey($groups, call_user_func($key, $item), $item);
        return $groups;
    }

    public static function groupByProperty($collection, $propertyName)
    {
        $groups = array();
        foreach ($collection as $item) {
            $key = $item->$propertyName;
            ArrayTools::pushToKey($groups, $key, $item);
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
            ArrayTools::pushToKey($groups, $key, $item);
        }
        return $groups;
    }
}

