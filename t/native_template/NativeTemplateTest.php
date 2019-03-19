<?php

class NativeTemplateTest extends PHPUnit_Framework_TestCase
{
    public function testEscaping()
    {
        $this->expectOutputString('');

        $nt = new \PFF\NativeTemplate(array(dirname(__FILE__)));
        $this->assertEquals(
            '<b>\'test\', &quot;test&quot; &amp; test.</b>' . "\n" . '<em>1</em>' . "\n" . '<em>2</em>',
            trim($nt->render('simple.html', array('text' => '\'test\', "test" & test.'))));

    }

    public function testErrorCatching()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessageRegExp('/undefined_variable/');
        $this->expectOutputString('');

        $nt = new \PFF\NativeTemplate(array(dirname(__FILE__)));
        $nt->render('syntax_error.html');
    }

    public function testBadFile()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('/not found/');
        $this->expectOutputString('');

        $nt = new \PFF\NativeTemplate(array(dirname(__FILE__)));
        $nt->render('unknown.html');
    }

    public function testLayout()
    {
        $this->expectOutputString('');

        $nt = new \PFF\NativeTemplate(array(dirname(__FILE__)));
        $rendered = $nt->render('page.html', array('title' => 'Page Title', 'content' => 'Page Content'));

        $this->assertEquals(<<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Title</title>
</head>
<body>
    <div class="page">
    Page Content</div>
</body>
</html>

HTML
            ,
            $rendered);
    }
}
