<?php

class ContainerTest extends PHPUnit_Framework_TestCase
{
    public function testValue()
    {
        \PFF\Container::set('text', 'Hello, World');
        $this->assertEquals('Hello, World', \PFF\Container::text());
    }

    public function testFunction()
    {
        \PFF\Container::setFunction('wrap', function($str) { return '('.$str.')'; });
        $this->assertEquals('(i love lisp)', \PFF\Container::wrap('i love lisp'));
    }

    public function testFactory()
    {
        \PFF\Container::setFactory('wrapOnce', function($str) { return '('.$str.')'; });
        $this->assertEquals('(i love lisp)', \PFF\Container::wrapOnce('i love lisp'));
        $this->assertEquals('(i love lisp)', \PFF\Container::wrapOnce('i hate lisp'));
    }
}

