<?php

class RegexpTest extends \PHPUnit\Framework\TestCase
{
    function testEmail()
    {
        $this->assertTrue(preg_match(\PFF\Regexp::email(), "a@b.com") !== 0);
        $this->assertTrue(preg_match(\PFF\Regexp::email(), " a @ b . com ") === 0);
    }

    function testUrl()
    {
        $this->assertTrue(preg_match(\PFF\Regexp::url(), "mailto:a@b.com") === 0);
        $this->assertTrue(preg_match(\PFF\Regexp::url(), "https://ab.com") !== 0);
    }
}
