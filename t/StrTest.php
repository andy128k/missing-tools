<?php

class StrTest extends \PHPUnit\Framework\TestCase
{
    public function testStartsWith()
    {
        $this->assertTrue(\PFF\Str::startsWith('abcdef', 'abc'));
        $this->assertFalse(\PFF\Str::startsWith(' abcdef', 'abc'));
        $this->assertFalse(\PFF\Str::startsWith('abc', 'abcdef'));
        $this->assertTrue(\PFF\Str::startsWith('abc', 'abc'));
        $this->assertTrue(\PFF\Str::startsWith('abc', ''));
        $this->assertFalse(\PFF\Str::startsWith('abc', ' '));
    }

    public function testEndsWith()
    {
        $this->assertTrue(\PFF\Str::endsWith('abcdef', 'def'));
        $this->assertFalse(\PFF\Str::endsWith('abcdef ', 'def'));
        $this->assertFalse(\PFF\Str::endsWith('abc', 'abcdef'));
        $this->assertTrue(\PFF\Str::endsWith('abc', 'abc'));
        $this->assertTrue(\PFF\Str::endsWith('abc', ''));
        $this->assertFalse(\PFF\Str::endsWith('abc', ' '));
    }
}

