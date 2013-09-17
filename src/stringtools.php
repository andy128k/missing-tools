<?php

final class StringTools
{
    public static function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return !$length || substr($haystack, -$length) === $needle;
    }
}

