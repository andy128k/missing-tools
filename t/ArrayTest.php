<?php

class ArrayToolsTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $arr = array(
            'apple' => 100,
            'grapefruit' => 400,
            'carrot' => 50,
        );

        $this->assertEquals(400, ArrayTools::get($arr, 'grapefruit'));
        $this->assertEquals(null, ArrayTools::get($arr, 'orange'));
        $this->assertEquals('no-oranges', ArrayTools::get($arr, 'orange', 'no-oranges'));
    }

    public function testEnsureKeyExists()
    {
        $arr = array(
            'apple' => 100,
            'grapefruit' => 400,
            'carrot' => 50,
        );

        $this->assertEquals(false, array_key_exists('orange', $arr));
        ArrayTools::ensureKeyExists($arr, 'orange');
        $this->assertEquals(true, array_key_exists('orange', $arr));
    }

    public function testPushToKey()
    {
        $arr = array('carrot' => array('orange'));

        ArrayTools::pushToKey($arr, 'apple', 'green');
        ArrayTools::pushToKey($arr, 'carrot', 'long');
        ArrayTools::pushToKey($arr, 'apple', 'sweet');

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

        $apple = ArrayTools::pop($arr, 'apple');
        $this->assertEquals(false, array_key_exists('apple', $arr));
        $this->assertEquals(100, $apple);

        $apple = ArrayTools::pop($arr, 'apple');
        $this->assertEquals(null, $apple);

        $apple = ArrayTools::pop($arr, 'apple', 'what?');
        $this->assertEquals('what?', $apple);
    }
}

