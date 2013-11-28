<?php

namespace HtmlBuilder;

class Tag
{
    private $name, $selfClose, $attributes, $inner;

    public function __construct($name, $attributes=array(), $inner=array())
    {
        $this->name = $name;
        $this->attributes = (array)$attributes;
        $this->inner = (array)$inner;
        $this->selfClose = in_array($name, array("base", "basefont", "br", "col", "frame", "hr", "input", "link", "meta", "param"));
    }

    public static function __callStatic($method, $args)
    {
        $attributes = array_shift($args);
        $inner = array_shift($args);
        return new Tag($method, $attributes, $inner);
    }

    public function attr($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function unsetAttr($name)
    {
        unset($this->attributes[$name]);
    }

    private function getListAttribute($name)
    {
        $classes = isset($this->attributes[$name]) ? $this->attributes[$name] : array();
        if (!is_array($classes))
            $classes = explode(' ', $classes);
        return $classes;
    }
    
    public function addClass($class)
    {
        $classes = $this->getListAttribute('class');
        if (!in_array($class, $classes))
            $classes[] = $class;
        $this->attributes['class'] = $classes;
    }

    public function removeClass($class)
    {
        $classes = $this->getListAttribute('class');
        $k = array_search($class, $classes);
        if ($k !== false)
            unset($classes[$k]);
        $this->attributes['class'] = $classes;
    }

    public function append($item)
    {
        if ($item instanceof Tag)
            $this->inner[] = $item;
        else
            $this->inner[] = htmlspecialchars($item, ENT_COMPAT, 'UTF-8');
        return $this;
    }

    public function raw($raw)
    {
        $this->inner[] = $raw;
        return $this;
    }

    public function html()
    {
        $s = '<'.$this->name;
        foreach ($this->attributes as $k => $v) {
            if (is_array($v))
                $v = implode(' ', $v);
            $s .= ' '.$k.'="'.htmlspecialchars($v, ENT_COMPAT, 'UTF-8').'"';
        }
        $s .= '>';

        if (!$this->selfClose) {
            foreach ($this->inner as $item)
                $s .= (string)$item;
            $s .= '</'.$this->name.'>';
        }

        return $s;
    }

    public function __toString()
    {
        return $this->html();
    }
}

