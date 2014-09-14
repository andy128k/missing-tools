<?php

class MultidimensionalArrayTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $arr = new \PFF\MultidimensionalArray;
        $arr->set('fruit', 'apple', 100);
        $arr->set('fruit', 'grapefruit', 400);
        $arr->set('vegetable', 'carrot', 50);

        $this->assertEquals(400, $arr->get('fruit', 'grapefruit'));
        $this->assertEquals(null, $arr->get('fruit', 'orange'));
        $this->assertEquals(null, $arr->get('apple', 'fruit'));
    }

    public function testEnsureKeyExists()
    {
        $arr = new \PFF\MultidimensionalArray;
        $arr->set('fruit', 'apple', 100);
        $arr->set('fruit', 'grapefruit', 400);
        $arr->set('vegetable', 'carrot', 50);

        $this->assertEquals(false, $arr->contains('orange'));
        $arr->ensureKeyExists(array('orange'));
        $this->assertEquals(true, $arr->contains('orange'));
    }

    public function testPushToKey()
    {
        $arr = new \PFF\MultidimensionalArray;
        $arr->set('vegetable', 'carrot', array('orange'));

        $arr->pushToKey(array('fruit', 'apple'), 'green')
            ->pushToKey(array('vegetable', 'carrot'), 'long')
            ->pushToKey(array('fruit', 'apple'), 'sweet');

        $this->assertEquals(array('green', 'sweet'), $arr->get('fruit', 'apple'));
        $this->assertEquals(array('orange', 'long'), $arr->get('vegetable', 'carrot'));
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
}

