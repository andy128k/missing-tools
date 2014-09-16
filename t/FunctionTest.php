<?php

use \PFF\Functions as F;
use \PFF\Placeholder as P;

function second()
{
    return func_get_arg(1);
}

class FunctionTest extends PHPUnit_Framework_TestCase
{
    public function testBind()
    {
        $f = \PFF\BoundFunc::create('str_replace', ' ', '_', P::p());
        $this->assertEquals('no_space', $f('no space'));

        $f = \PFF\BoundFunc::create('sprintf', '[%s:%s:%s]', '[', P::p(), ']');
        $this->assertEquals('[[:Abc:]]', $f('Abc'));
    }

    public function testCompose()
    {
        $f = F::func('abs')
            ->then('sqrt');

        $this->assertEquals(2, $f(4));
        $this->assertEquals(2, $f(-4));
        $this->assertEquals(9, $f(81));
    }

    public function testChain()
    {
        $before = ' text  with_spaces  underscores  and-hypens  ';
        $after = '_text_with_spaces_underscores_and_hypens_';

        $f = F::bind('str_replace', ' ', '_', P::p())
            ->then(F::bind('str_replace', '-', '_', P::p()))
            ->then(F::bind('str_replace', '__', '_', P::p()));

        $this->assertEquals($after, $f($before));


        $f = F::identity()
            ->thenBind('str_replace', ' ', '_', P::p())
            ->thenBind('str_replace', '-', '_', P::p())
            ->thenBind('str_replace', '__', '_', P::p());

        $this->assertEquals($after, $f($before));
    }

    public function testArrayBindDefault()
    {
        $f = F::bind(array('PFF\Arr', 'get'), P::p(1), P::p(2), 'DEFAULT');

        $arr = array(
            'apple' => 100,
            'grapefruit' => 400,
            'carrot' => 50,
        );

        $this->assertEquals(400, $f($arr, 'grapefruit'));
        $this->assertEquals('DEFAULT', $f($arr, 'orange'));
        $this->assertEquals('DEFAULT', $f($arr, 'orange', 'no-oranges'));
    }

    public function testGetter()
    {
        $arr = array('name' => 'apple', 'weight' => 100);

        $getter = F::bind(array('PFF\Arr', 'get'), P::p(2), P::p(1));
        $getName = $getter('name');
        $getWeight = $getter->call('weight');

        $this->assertEquals('apple', $getName($arr));
        $this->assertEquals(100, $getWeight($arr));
    }

    public function testPluck0()
    {
        $getName = F::bind(array('PFF\Arr', 'get'), P::p(), 'name');
        $f = F::bind('array_map', $getName, P::p());

        $arr = array(
            array('name' => 'apple', 'weight' => 100),
            array('name' => 'grapefruit', 'weight' => 400),
            array('name' => 'carrot', 'weight' => 50),
        );

        $this->assertEquals(array('apple', 'grapefruit', 'carrot'), $f($arr));
    }

    public function testPluck1()
    {
        $f = F::compose(
            F::curry('array_map', 2),
            F::bind(array('PFF\Arr', 'get'), P::p(2), P::p(1)));

        $arr = array(
            array('name' => 'apple', 'weight' => 100),
            array('name' => 'grapefruit', 'weight' => 400),
            array('name' => 'carrot', 'weight' => 50),
        );

        $this->assertEquals(array('apple', 'grapefruit', 'carrot'), $f->call('name')->call($arr));
    }

    public function testPluck2()
    {
        $second = F::func('second');
        $getter = F::bind(array('PFF\Arr', 'get'), P::p(2), P::p(1));
        $pluck = F::S(
            F::bind('array_map', P::p(3), P::p(1)),
            F::compose($getter, $second));

        $arr = array(
            array('name' => 'apple', 'weight' => 100),
            array('name' => 'grapefruit', 'weight' => 400),
            array('name' => 'carrot', 'weight' => 50),
        );

        $this->assertEquals(array('apple', 'grapefruit', 'carrot'), $pluck($arr, 'name'));
    }
}

