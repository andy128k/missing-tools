<?php

namespace PFF;

final class MapLike
{
    public static function get($mapLike, $key, $default = null)
    {
        if (is_array($mapLike)) {
            if (array_key_exists($key, $mapLike)) {
                return $mapLike[$key];
            } else {
                return $default;
            }
        } elseif (is_object($mapLike)) {
            try {
                return $mapLike->$key;
            } catch (\Exception $e) {
                return $default;
            }
        } else {
            throw new \Exception("Not a map");
        }
    }
}
