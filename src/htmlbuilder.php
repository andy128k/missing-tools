<?php

namespace PFF\HtmlBuilder;

class Text
{
    /**
     * @param string $text
     * @return string
     */
    public static function escape($text)
    {
        return htmlspecialchars($text, ENT_COMPAT, 'UTF-8');
    }
}

class Tag
{
    private $name, $selfClose, $attributes, $inner = array();

    /**
     * @param string $name
     * @param array $attributes
     * @param mixed|null $inner
     */
    public function __construct($name, $attributes = array(), $inner = null)
    {
        $this->name = $name;
        $this->attributes = (array)$attributes;
        $this->selfClose = in_array($name, array("base", "basefont", "br", "col", "frame", "hr", "input", "link", "meta", "param"));

        if (!is_array($inner))
            $inner = array($inner);
        foreach ($inner as $item)
            $this->append($item);
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param array $inner
     * @return Tag
     */
    public static function create($name, $attributes = array(), ...$inner)
    {
        return new Tag($name, $attributes, $inner);
    }

    /**
     * @param string $name
     * @param array $args
     * @return Tag
     */
    public static function __callStatic($name, $args)
    {
        $attributes = array_shift($args);
        return new Tag($name, $attributes, $args);
    }

    /**
     * @param string $name
     * @param string $value
     * @return Tag
     */
    public function attr($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return Tag
     */
    public function unsetAttr($name)
    {
        unset($this->attributes[$name]);
        return $this;
    }

    /**
     * @param array $attributes dictionary
     * @return Tag
     */
    public function attrs(array $attributes)
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

    /**
     * @param string $class
     * @return Tag
     */
    public function addClass($class)
    {
        $classes = $this->getListAttribute('class');
        if (!in_array($class, $classes))
            $classes[] = $class;
        $this->attributes['class'] = $classes;
        return $this;
    }

    /**
     * @param string $class
     * @return Tag
     */
    public function removeClass($class)
    {
        $classes = $this->getListAttribute('class');
        $k = array_search($class, $classes);
        if ($k !== false)
            unset($classes[$k]);
        $this->attributes['class'] = $classes;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param boolean $condition
     * @return Tag
     */
    public function toggleAttr($name, $value, $condition)
    {
        return $condition
            ? $this->attr($name, $value)
            : $this->unsetAttr($name);
    }

    /**
     * @param string $class
     * @param boolean $condition
     * @return Tag
     */
    public function toggleClass($class, $condition)
    {
        return $condition
            ? $this->addClass($class)
            : $this->removeClass($class);
    }

    /**
     * @param mixed $item
     * @return Tag
     */
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

    /**
     * @param string|array $raw
     * @return Tag
     */
    public function raw($raw)
    {
        if (is_array($raw)) {
            $this->inner = array_merge($this->inner, \PFF\Arr::flatten($raw));
        } else {
            $this->inner[] = $raw;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function html()
    {
        $s = '<' . $this->name;
        foreach ($this->attributes as $k => $v) {
            if (is_array($v))
                $v = implode(' ', $v);
            $s .= ' ' . $k . '="' . Text::escape($v) . '"';
        }
        $s .= '>';

        if (!$this->selfClose) {
            foreach ($this->inner as $item)
                $s .= (string)$item;
            $s .= '</' . $this->name . '>';
        }

        return $s;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->html();
    }
}
