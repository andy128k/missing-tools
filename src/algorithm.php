<?php

namespace PFF;

class TopSort
{
    private $following, $visited = array(), $result = array();

    private function getFollowing($obj)
    {
        return call_user_func($this->following, $obj);
    }

    private function once($obj)
    {
        if (in_array($obj, $this->visited)) {
            return false;
        } else {
            $this->visited[] = $obj;
            return true;
        }
    }

    private function visit($obj, $chain)
    {
        if (in_array($obj, $chain)) {
            $chain[] = $obj;
            return $chain;
        }

        if ($this->once($obj)) {
            $chain[] = $obj;
            foreach ($this->getFollowing($obj) as $following) {
                $loop = $this->visit($following, $chain);
                if ($loop !== false)
                    return $loop;
            }
            $this->result[] = $obj;
        }
        return false;
    }

    public static function sort($initialNodes, $following)
    {
        $sort = new self;
        $sort->following = $following;

        foreach ($initialNodes as $start) {
            $loop = $sort->visit($start, array());
            if ($loop !== false)
                return array(false, $loop);
        }
        return array(true, $sort->result);
    }
}

