<?php

class HtmlBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testTag()
    {
        $tag = \PFF\HtmlBuilder\Tag::div(array('class' => 'container'))
            ->append('Hello, "World"')
            ->append(\PFF\HtmlBuilder\Tag::a()->append('click ')->raw('<b>me</b>'));

        $this->assertEquals('<div class="container">Hello, &quot;World&quot;<a>click <b>me</b></a></div>', (string)$tag);
    }
}

