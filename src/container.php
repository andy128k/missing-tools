<?php

namespace PFF;

// Dependency injection container

class Container
{
    private $values = array();

    public function getWithArgs($id, $args)
    {
        if (!array_key_exists($id, $this->values))
            throw new \InvalidArgumentException('Identifier "'.$id.'" is not defined.');

        $v = $this->values[$id];
        if ($v[0] == 'function') {
            return call_user_func_array($v[1], $args);
        } elseif ($v[0] == 'factory') {
            $value = call_user_func_array($v[1], $args);
            $this->values[$id] = array('value', $value);
            return $value;
        } else /*value*/ {
            return $v[1];
        }
    }

    public function get()
    {
        $args = func_get_args();
        $id = array_shift($args);
        return $this->getWithArgs($id, $args);
    }

    public function __call($name, $args)
    {
        switch ($name) {
        case 'set':
            $this->values[$args[0]] = array('value', $args[1]);
            break;
        case 'setFunction':
            $this->values[$args[0]] = array('function', $args[1]);
            break;
        case 'setFactory':
            $this->values[$args[0]] = array('factory', $args[1]);
            break;
        default:
            return $this->getWithArgs($name, $args);
        }
    }

    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new self;
        return self::$instance;
    }

    public static function __callStatic($name, $args)
    {
        return call_user_func_array(array(self::getInstance(), $name), $args);
    }
}

