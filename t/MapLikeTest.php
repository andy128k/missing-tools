<?php

class MapLikeTest extends PHPUnit_Framework_TestCase
{
    function testGetArray()
    {
        $map = ['a' => '1', 'b' => 2];

        $this->assertEquals('1', \PFF\MapLike::get($map, 'a'));
        $this->assertEquals(33, \PFF\MapLike::get($map, 'c', 33));
    }

    function testGetObject()
    {
        $map = (object)['a' => '1', 'b' => 2];

        $this->assertEquals('1', \PFF\MapLike::get($map, 'a'));
        $this->assertEquals(33, \PFF\MapLike::get($map, 'c', 33));
    }

    function testGetUnsupported()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not a map');
        \PFF\MapLike::get('map', 'a');
    }
}
