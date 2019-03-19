<?php

class ArrTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $arr = array(
            'apple' => 100,
            'grapefruit' => 400,
            'carrot' => 50,
        );

        $this->assertEquals(400, \PFF\Arr::get($arr, 'grapefruit'));
        $this->assertEquals(null, \PFF\Arr::get($arr, 'orange'));
        $this->assertEquals('no-oranges', \PFF\Arr::get($arr, 'orange', 'no-oranges'));
    }

    public function testEnsureKeyExists()
    {
        $arr = array(
            'apple' => 100,
            'grapefruit' => 400,
            'carrot' => 50,
        );

        $this->assertEquals(false, array_key_exists('orange', $arr));
        \PFF\Arr::ensureKeyExists($arr, 'orange');
        $this->assertEquals(true, array_key_exists('orange', $arr));
    }

    public function testPushToKey()
    {
        $arr = array('carrot' => array('orange'));

        \PFF\Arr::pushToKey($arr, 'apple', 'green');
        \PFF\Arr::pushToKey($arr, 'carrot', 'long');
        \PFF\Arr::pushToKey($arr, 'apple', 'sweet');

        $this->assertEquals(array('green', 'sweet'), $arr['apple']);
        $this->assertEquals(array('orange', 'long'), $arr['carrot']);
    }

    public function testPop()
    {
        $arr = array(
            'apple' => 100,
            'grapefruit' => 400,
            'carrot' => 50,
        );

        $apple = \PFF\Arr::pop($arr, 'apple');
        $this->assertEquals(false, array_key_exists('apple', $arr));
        $this->assertEquals(100, $apple);

        $apple = \PFF\Arr::pop($arr, 'apple');
        $this->assertEquals(null, $apple);

        $apple = \PFF\Arr::pop($arr, 'apple', 'what?');
        $this->assertEquals('what?', $apple);
    }

    public function testFlatten()
    {
        $arr = array('a', 'b', array(array(array('x'), 'y', 'z')), array(array('p')));
        $flat = \PFF\Arr::flatten($arr);
        $this->assertEquals(array('a', 'b', 'x', 'y', 'z', 'p'), $flat);
    }

    public function testPluck()
    {
        $arr = array(
            (object)array('name' => 'apple',      'color' => 'green'),
            (object)array('name' => 'carrot',     'color' => 'red'),
            (object)array('name' => 'tomato',     'color' => 'red'),
            (object)array('name' => 'grapefruit', 'color' => 'green'),
        );
        $colors = \PFF\Arr::pluck($arr, 'color');
        $this->assertEquals(4, count($colors));
        $this->assertEquals(['green', 'red', 'red', 'green'], $colors);
    }
}
