<?php

namespace PFF;

class Functions
{
    static function identity()
    {
        return new IdentityFunc;
    }

    static function func($func)
    {
        if ($func instanceof Func)
            return $func;
        else
            return new Func($func);
    }

    static function bind()
    {
        $args = func_get_args();
        $func = array_shift($args);
        return new BoundFunc($func, $args);
    }

    static function curry($func, $arity)
    {
        return new BoundFunc($func, Placeholder::range($arity));
    }

    static function compose($f, $g)
    {
        return new \PFF\CompositionFunc($f, $g);
    }

    static function S($f, $g)
    {
        return new \PFF\SFunc($f, $g);
    }
}

abstract class AbstractFunc
{
    abstract function apply($args);

    function call()
    {
        return $this->apply(func_get_args());
    }

    function __invoke()
    {
        return $this->apply(func_get_args());
    }

    /* compose */

    function then($then)
    {
        return new CompositionFunc($then, $this);
    }

    function thenBind()
    {
        $args = func_get_args();
        $func = array_shift($args);
        return $this->then(BoundFunc::createv($func, $args));
    }
}

class Func extends AbstractFunc
{
    private $f;

    function __construct($f)
    {
        $this->f = $f;
    }

    function apply($args)
    {
        return call_user_func_array($this->f, $args);
    }
}

class IdentityFunc extends AbstractFunc
{
    function apply($args)
    {
        if (count($args) !== 1)
            throw new \Exception('Wrong number of arguments. Only one is expected.');
        return $args[0];
    }
}


class Placeholder
{
    public $place;

    function __construct($place)
    {
        $this->place = $place;
    }

    static function p($place=1)
    {
        return new self($place);
    }

    static function range($count)
    {
        $range = array();
        for ($i = 1; $i <= $count; ++$i)
            $range[] = self::p($i);
        return $range;
    }
}

class BoundFunc extends Func
{
    private $args, $arity;

    function __construct($f, $args=null)
    {
        parent::__construct($f);
        $this->args = $args;
        $this->arity = 0;
        foreach ($this->args as $p)
            if ($p instanceof Placeholder)
                $this->arity = max($this->arity, $p->place);
    }

    static function createv($func, $args)
    {
        return new BoundFunc($func, $args);
    }

    static function create()
    {
        $args = func_get_args();
        $func = array_shift($args);
        return self::createv($func, $args);
    }

    function apply($args)
    {
        if (count($args) < $this->arity) {
            $px = Placeholder::range($this->arity - count($args));
            return new self($this, array_merge($args, $px));
        }

        $a = array();
        foreach ($this->args as $p) {
            if ($p instanceof Placeholder) {
                $a[] = $args[$p->place - 1];
            } else {
                $a[] = $p;
            }
        }
        return parent::apply($a);
    }
}

class CompositionFunc extends Func
{
    private $left;

    function __construct($left, $right)
    {
        parent::__construct($right);
        $this->left = Functions::func($left);
    }

    function apply($args)
    {
        return $this->left->call(parent::apply($args));
    }
}

class SFunc extends Func
{
    private $left;

    function __construct($left, $right)
    {
        parent::__construct($right);
        $this->left = Functions::func($left);
    }

    function apply($args)
    {
        $r = parent::apply($args);
        $args[] = $r;
        return $this->left->apply($args);
    }
}

