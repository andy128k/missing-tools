<?php

namespace PFF\HtmlBuilder;

class Text
{
    public static function escape($text)
    {
        return htmlspecialchars($text, ENT_COMPAT, 'UTF-8');
    }
}

class Tag
{
    private $name, $selfClose, $attributes, $inner=array();

    public function __construct($name, $attributes=array(), $inner=null)
    {
        $this->name = $name;
        $this->attributes = (array)$attributes;
        $this->selfClose = in_array($name, array("base", "basefont", "br", "col", "frame", "hr", "input", "link", "meta", "param"));

        if (!is_array($inner))
            $inner = array($inner);
        foreach ($inner as $item)
            $this->append($item);
    }

    public static function create(/*name[, attributes, [... inner]]*/)
    {
        $args = func_get_args();
        $name = array_shift($args);
        $attributes = array_shift($args);
        return new Tag($name, $attributes, $args);
    }

    public static function __callStatic($method, $args)
    {
        $attributes = array_shift($args);
        return new Tag($method, $attributes, $args);
    }

    public function attr($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    public function unsetAttr($name)
    {
        unset($this->attributes[$name]);
        return $this;
    }

    public function attrs($attributes)
    {
        foreach ($attributes as $k => $v) {
            if ($k == 'class') {
                if (!is_array($v))
                    $v = explode(' ', $v);
                foreach ($v as $class)
                    $this->addClass($class);
            } else {
                $this->attributes[$k] = $v;
            }
        }
        return $this;
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
        return $this;
    }

    public function removeClass($class)
    {
        $classes = $this->getListAttribute('class');
        $k = array_search($class, $classes);
        if ($k !== false)
            unset($classes[$k]);
        $this->attributes['class'] = $classes;
        return $this;
    }

    public function toggleAttr($name, $value, $condition)
    {
        return $condition
            ? $this->attr($name, $value)
            : $this->unsetAttr($name);
    }

    public function toggleClass($class, $condition)
    {
        return $condition
            ? $this->addClass($class)
            : $this->removeClass($class);
    }

    public function append($item)
    {
        if (is_array($item)) {
            foreach ($item as $i)
                $this->append($i);
        } elseif ($item instanceof Tag) {
            $this->inner[] = $item;
        } else {
            $this->inner[] = Text::escape($item);
        }
        return $this;
    }

    public function raw($raw)
    {
        if (is_array($raw)) {
            $this->inner = array_merge($this->inner, \PFF\Arr::flatten($raw));
        } else {
            $this->inner[] = $raw;
        }
        return $this;
    }

    public function html()
    {
        $s = '<'.$this->name;
        foreach ($this->attributes as $k => $v) {
            if (is_array($v))
                $v = implode(' ', $v);
            $s .= ' '.$k.'="'.Text::escape($v).'"';
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

