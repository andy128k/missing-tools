<?php

class MapTest extends \PHPUnit\Framework\TestCase
{
    public function testGet()
    {
        $map = new \PFF\Map;
        $map->set(['fruit', 'apple'], 100);
        $map->set(['fruit', 'grapefruit'], 400);
        $map->set(['vegetable', 'carrot'], 50);

        $this->assertEquals(3, $map->count());
        $this->assertEquals(true, $map->contains(['fruit', 'grapefruit']));
        $this->assertEquals(400, $map->get(['fruit', 'grapefruit']));
        $this->assertEquals(null, $map->get(['fruit', 'orange']));
        $this->assertEquals(false, $map->contains(['apple', 'fruit']));
        $this->assertEquals(null, $map->get(['apple', 'fruit']));

        $this->assertEquals(50, $map->get(['vegetable', 'carrot']));
        $map->set(['vegetable', 'carrot'], 150);
        $this->assertEquals(150, $map->get(['vegetable', 'carrot']));
        $map->remove(['vegetable', 'carrot']);
        $this->assertEquals(false, $map->contains(['vegetable', 'carrot']));
    }

    public function testDebugInfo()
    {
        $map = new \PFF\Map;
        $map->set(['fruit', 'apple'], 100);
        $output = $this->varDumpToString($map);
        $this->assertStringContainsString('fruit', $output);
        $this->assertStringContainsString('apple', $output);
        $this->assertStringContainsString('100', $output);
    }

    private function varDumpToString($anything)
    {
        ob_start();
        var_dump($anything);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function testUpdate()
    {
        $map = new \PFF\Map;
        $map->set('carrot', 1);
        $map->update('carrot', function ($c) {
            return $c + 1;
        });
        $map->update('tomato', function ($c) {
            return $c + 1;
        });
        $this->assertEquals(2, $map->get('carrot'));
        $this->assertEquals(null, $map->get('tomato'));
    }

    public function testUpdateOrInsert()
    {
        $map = new \PFF\Map;
        $map->set('carrot', 1);
        $map->updateOrInsert('carrot', function ($c) {return $c + 1;}, 1);
        $map->updateOrInsert('tomato', function ($c) {return $c + 1;}, 1);
        $this->assertEquals(2, $map->get('carrot'));
        $this->assertEquals(1, $map->get('tomato'));
    }

    public function testUpdateOrInsertArray()
    {
        $map = new \PFF\Map;
        $map->set('carrot', [1]);
        $map->updateOrInsert('carrot', function ($c) {return array_merge($c, [1]);}, [1]);
        $map->updateOrInsert('tomato', function ($c) {return array_merge($c, [1]);}, [1]);
        $this->assertEquals([1, 1], $map->get('carrot'));
        $this->assertEquals([1], $map->get('tomato'));
    }
}
