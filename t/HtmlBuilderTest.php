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

    public function testAttrs()
    {
        $tag = \PFF\HtmlBuilder\Tag::div(array('class' => 'container'))
            ->attrs(['data-somedata' => '', 'class' => 'red', 'rel' => 'what?'])
            ->append(['Hello', ', "World"'])
            ->append(\PFF\HtmlBuilder\Tag::create('a', ['href' => '#', 'onClick' => 'clicked()'], 'cli', 'ck', ' ')->raw('<b>me</b>'))
            ->raw(['&nbsp;', '&mdash;'])
            ->append((new \PFF\HtmlBuilder\Tag('span', null, 'Thanks!'))->unsetAttr('font'));

        $tag->addClass('button')
            ->removeClass('container');
        $tag->toggleAttr("data-somedata", "somedatavalue", true);
        $tag->toggleClass('red', false);
        $tag->toggleClass('red', true);
        $tag->toggleClass('red', false);
        $tag->unsetAttr('rel');

        $this->assertEquals('<div class="button" data-somedata="somedatavalue">Hello, &quot;World&quot;<a href="#" onClick="clicked()">click <b>me</b></a>&nbsp;&mdash;<span>Thanks!</span></div>', (string)$tag);
    }
}
