<?php

class MapTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $arr = new \PFF\Map;
        $arr->set(['fruit', 'apple'], 100);
        $arr->set(['fruit', 'grapefruit'], 400);
        $arr->set(['vegetable', 'carrot'], 50);

        $this->assertEquals(3, $arr->count());
        $this->assertEquals(true, $arr->contains(['fruit', 'grapefruit']));
        $this->assertEquals(400, $arr->get(['fruit', 'grapefruit']));
        $this->assertEquals(null, $arr->get(['fruit', 'orange']));
        $this->assertEquals(false, $arr->contains(['apple', 'fruit']));
        $this->assertEquals(null, $arr->get(['apple', 'fruit']));

        $this->assertEquals(50, $arr->get(['vegetable', 'carrot']));
        $arr->set(['vegetable', 'carrot'], 150);
        $this->assertEquals(150, $arr->get(['vegetable', 'carrot']));
        $arr->remove(['vegetable', 'carrot']);
        $this->assertEquals(false, $arr->contains(['vegetable', 'carrot']));
    }

    public function testDebugInfo()
    {
        $arr = new \PFF\Map;
        $arr->set(['fruit', 'apple'], 100);
        $output = $this->varDumpToString($arr);
        $this->assertContains('fruit', $output);
        $this->assertContains('apple', $output);
        $this->assertContains('100', $output);
    }

    private function varDumpToString($anything)
    {
        ob_start();
        var_dump($anything);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
