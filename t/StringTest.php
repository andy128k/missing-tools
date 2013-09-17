<?php

class StringToolsTest extends PHPUnit_Framework_TestCase
{
    public function testStartsWith()
    {
        $this->assertTrue(StringTools::startsWith('abcdef', 'abc'));
        $this->assertFalse(StringTools::startsWith(' abcdef', 'abc'));
        $this->assertFalse(StringTools::startsWith('abc', 'abcdef'));
        $this->assertTrue(StringTools::startsWith('abc', 'abc'));
        $this->assertTrue(StringTools::startsWith('abc', ''));
        $this->assertFalse(StringTools::startsWith('abc', ' '));
    }

    public function testEndsWith()
    {
        $this->assertTrue(StringTools::endsWith('abcdef', 'def'));
        $this->assertFalse(StringTools::endsWith('abcdef ', 'def'));
        $this->assertFalse(StringTools::endsWith('abc', 'abcdef'));
        $this->assertTrue(StringTools::endsWith('abc', 'abc'));
        $this->assertTrue(StringTools::endsWith('abc', ''));
        $this->assertFalse(StringTools::endsWith('abc', ' '));
    }
}

