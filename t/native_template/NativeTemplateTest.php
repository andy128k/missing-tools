<?php

class NativeTemplateTest extends PHPUnit_Framework_TestCase
{
    public function testEscaping()
    {
        $nt = new \PFF\NativeTemplate(array(dirname(__FILE__)));
        $this->assertEquals(
            '<b>\'test\', &quot;test&quot; &amp; test.</b>',
            trim($nt->render('simple.html', array('text' => '\'test\', "test" & test.'))));
    }

    public function testErrorCatching()
    {
        $nt = new \PFF\NativeTemplate(array(dirname(__FILE__)));

        $this->setExpectedExceptionRegExp('ErrorException', '/undefined_variable/');
        $this->expectOutputString('');
        $nt->render('syntax_error.html');
    }
}

