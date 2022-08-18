<?php

class TestArrayAccess implements ArrayAccess
{
    private $container = array();

    public function __construct($data) {
        $this->container = $data;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return array_key_exists($offset, $this->container);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return $this->container[$offset];
    }
}

class MapLikeTest extends \PHPUnit\Framework\TestCase
{
    function testGetArray()
    {
        $map = ['a' => '1', 'b' => 2, 'n' => null];

        $this->assertEquals('1', \PFF\MapLike::get($map, 'a'));
        $this->assertEquals(33, \PFF\MapLike::get($map, 'c', 33));
        $this->assertEquals(null, \PFF\MapLike::get($map, 'n', 'not null'));
    }

    function testGetArrayAccess()
    {
        $map = new TestArrayAccess(['a' => '1', 'b' => 2, 'n' => null]);

        $this->assertEquals('1', \PFF\MapLike::get($map, 'a'));
        $this->assertEquals(33, \PFF\MapLike::get($map, 'c', 33));
        $this->assertEquals(null, \PFF\MapLike::get($map, 'n', 'not null'));
    }

    function testGetObject()
    {
        $map = (object)['a' => '1', 'b' => 2, 'n' => null];

        $this->assertEquals('1', \PFF\MapLike::get($map, 'a'));
        $this->assertEquals(33, \PFF\MapLike::get($map, 'c', 33));
        $this->assertEquals(null, \PFF\MapLike::get($map, 'n', 'not null'));
    }

    function testGetUnsupported()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Not a map');
        \PFF\MapLike::get('map', 'a');
    }
}
