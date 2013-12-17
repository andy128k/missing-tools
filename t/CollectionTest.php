<?php

class CollectionTest extends PHPUnit_Framework_TestCase
{
    public function testColumns()
    {
        $arr = array(
            'apple',
            'grapefruit',
            'carrot',
            'tomato',
        );
        $columns = \PFF\Collection::columns($arr, 3);
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
        $chunks = \PFF\Collection::chunks($arr, 3);
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
        $chunks = \PFF\Collection::chunks($arr, 3, true);
        $this->assertEquals(2, count($chunks));
        $this->assertEquals(3, count($chunks[0]));
        $this->assertEquals(3, count($chunks[1]));
    }
}

