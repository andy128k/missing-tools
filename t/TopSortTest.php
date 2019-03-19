<?php

use PFF\Arr;

class TopSortTest extends PHPUnit_Framework_TestCase
{
    function testAlchemy()
    {
        $nodes = [
            'time' => ['sand', 'glass'],
            'fruit' => ['sun', 'tree'],
            'stone' => ['air', 'lava'],
            'steam' => ['water', 'fire'],
            'lava' => ['earth', 'fire'],
            'sand' => ['air', 'stone'],
            'sun' => ['fire', 'sky'],
            'glass' => ['fire', 'sand'],
            'sky' => ['air', 'cloud'],
            'cloud' => ['air', 'steam'],
            'alcohol' => ['fruit', 'time'],
        ];

        list($success, $sorted) = \PFF\TopSort::sort(['alcohol'], function ($node) use ($nodes) {
            return Arr::get($nodes, $node, []);
        });

        $this->assertTrue($success, 'Sorted successfully');
        $this->assertEquals([
            'fire',
            'air',
            'water',
            'steam',
            'cloud',
            'sky',
            'sun',
            'tree',
            'fruit',
            'earth',
            'lava',
            'stone',
            'sand',
            'glass',
            'time',
            'alcohol',
        ], $sorted);
    }

    function testLoop()
    {
        $nodes = [
            'a' => ['b', 'c'],
            'b' => ['c', 'a'],
            'c' => ['a', 'b'],
        ];

        list($success, $loop) = \PFF\TopSort::sort(['a'], function ($node) use ($nodes) {
            return Arr::get($nodes, $node, []);
        });

        $this->assertFalse($success, 'Sort impossible');
        $this->assertEquals(['a', 'b', 'c', 'a'], $loop);
    }
}
