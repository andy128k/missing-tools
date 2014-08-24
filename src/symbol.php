<?php

namespace PFF;

final class Symbol
{
    private static $symbols = array();
    private $name;

    private function __construct()
    {
    }

    function __clone()
    {
        throw new \Exception('Cloning of symbols is not allowed.');
    }

    function __wakeup()
    {
        throw new \Exception('Deserialization of symbols is not allowed.');
    }

    public function name()
    {
        return $this->name;
    }

    public static function intern($name)
    {
        if (array_key_exists($name, self::$symbols))
            return self::$symbols[$name];

        $symbol = new self;
        $symbol->name = $name;
        self::$symbols[$name] = $symbol;
        return $symbol;
    }

    public static function __callStatic($name, $args)
    {
        return self::intern($name);
    }
}

