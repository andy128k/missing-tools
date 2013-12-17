<?php

namespace PFF;

class Mixins
{
    private $methods = array();

    public function __construct($object, $mixins)
    {
        foreach ($mixins as $class) {
            if (!class_exists($class))
                throw new Exception('Tried to inherit non-existant class \''.$class.'\'.');

            $mixin = new $class($object);
            $methods = get_class_methods($mixin);
            if (is_array($methods))
                foreach ($methods as $method)
                    $this->methods[strtolower($method)][] = $mixin;
        }
    }

    private $current_call_method = null;
    private $current_call_mixins = null;

    public function __call($method, $args = array())
    {
        $m = strtolower($method);
        if (!isset($this->methods[$m]))
            throw new Exception('Call to undefined method ' . get_class($this) . "::$method()");

        $this->current_call_method = $method;
        $this->current_call_mixins = $this->methods[$m];
        $result = call_user_func_array(array($this, 'call_next_method'), $args);
        $this->current_call_method = null;
        $this->current_call_mixins = null;
        return $result;
    }

    public function call_next_method()
    {
        if ($this->current_call_mixins === null)
            throw new Exception('call_next_method is invoked outside of mixin method.');

        $mixin = array_pop($this->current_call_mixins);
        if ($mixin === null)
            throw new Exception('No next method for ' . get_class($this) . "::$method()");

        return call_user_func_array(array($mixin, $this->current_call_method), func_get_args());
    }
}

abstract class Mixable
{
    protected abstract function __mixins();

    protected $mixins;

    public function __construct()
    {
        $this->mixins = new Mixins($this, $this->__mixins());
    }

    public function __call($method, $args = array())
    {
        return call_user_func_array(array($this->mixins, $method), $args);
    }

    public function call_next_method()
    {
        return call_user_func_array(array($this->mixins, 'call_next_method'), func_get_args());
    }

    public function __clone()
    {
    }
}

class Mixin
{
    protected $self;

    public function __construct($self)
    {
        $this->self = $self;
    }

    protected function call_next_method()
    {
        return $this->self->call_next_method(func_get_args());
    }
}

