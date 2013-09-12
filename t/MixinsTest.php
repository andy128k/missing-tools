<?php

class SomeMixin extends Mixin
{
    public function getSmthUseful()
    {
        return 'a';
    }

    public function setB($newValue)
    {
        $this->self->b = $newValue;
    }
}

class WrapMixin extends Mixin
{
    public function getSmthUseful()
    {
        return '(' . $this->call_next_method() . ')';
    }

    public function doSmthElse($str)
    {
        return strrev($str);
    }
}

class Mixture extends Mixable
{
    protected function __mixins() { return array('SomeMixin', 'WrapMixin'); }

    public $b = 'b';

    public function doSmthElse($str)
    {
        return strtoupper($this->mixins->doSmthElse($str));
    }
}

class MixinsTest extends PHPUnit_Framework_TestCase
{
    public function testSetter()
    {
        $mixture = new Mixture;
        $mixture->setB('B');
        $this->assertEquals('B', $mixture->b);
    }

    public function testWrapping()
    {
        $mixture = new Mixture;
        $this->assertEquals('(a)', $mixture->getSmthUseful());
    }

    public function testOverriding()
    {
        $mixture = new Mixture;
        $this->assertEquals('LARCH', $mixture->doSmthElse('hCraL'));
    }
}

