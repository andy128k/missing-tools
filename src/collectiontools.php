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
}

