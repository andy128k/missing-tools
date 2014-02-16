<?php

class CollectionsTest extends PHPUnit_Framework_TestCase
{
    public function testColumns()
    {
        $arr = array(
            'apple',
            'grapefruit',
            'carrot',
            'tomato',
        );
        $columns = Collections::columns($arr, 3);
        $this->assertEquals(3, count($columns));
        $this->assertEquals(2, count($columns[0]));
        $this->assertEquals(1, count($columns[1]));
        $this->assertEquals(1, count($columns[2]));
    }

    public function testChunks()
    {
        $arr = array(
            'apple',
            'grapefruit',
            'carrot',
            'tomato',
        );
        $chunks = Collections::chunks($arr, 3);
        $this->assertEquals(2, count($chunks));
        $this->assertEquals(3, count($chunks[0]));
        $this->assertEquals(1, count($chunks[1]));
    }

    public function testChunksPad()
    {
        $arr = array(
            'apple',
            'grapefruit',
            'carrot',
            'tomato',
        );
        $chunks = Collections::chunks($arr, 3, true);
        $this->assertEquals(2, count($chunks));
        $this->assertEquals(3, count($chunks[0]));
        $this->assertEquals(3, count($chunks[1]));
    }

    public function testGroupByProperty()
    {
        $arr = array(
            (object)array('name' => 'apple',      'color' => 'green'),
            (object)array('name' => 'carrot',     'color' => 'red'),
            (object)array('name' => 'tomato',     'color' => 'red'),
            (object)array('name' => 'grapefruit', 'color' => 'green'),
        );
        $groups = Collections::groupByProperty($arr, 'color');
        $this->assertEquals(2, count($groups));
        $this->assertEquals(2, count($groups['red']));
        $this->assertEquals(2, count($groups['green']));
    }
}

