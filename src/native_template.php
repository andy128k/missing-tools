<?php

namespace PFF;

class NativeTemplate
{
    private $_dirs, $_context = array(), $_layout = null, $_content = '';

    public function __construct($dirs)
    {
        $this->_dirs = $dirs;
    }

    public function __get($key)
    {
        return array_key_exists($key, $this->_context) ? $this->_context[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->_context[$key] = $value;
    }

    public function render($file, $context=array())
    {
        $this->_context = $context;

        while ($file) {
            $this->_content = $this->renderFile($file);

            $file = $this->_layout;
            $this->_layout = null;
        }
        return $this->_content;
    }

    private function renderFile($file)
    {
        $filename = $this->findFile($file);

        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if (error_reporting() & $errno)
                throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        ob_start();
        try {
            include $filename;
        } catch (\Exception $e) {
            ob_end_clean();
            restore_error_handler();
            throw $e;
        }
        $output = ob_get_contents();
        ob_end_clean();
        restore_error_handler();
        return $output;
    }

    private function findFile($file)
    {
        foreach ($this->_dirs as $dir) {
            $f = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_file($f))
                return $f;
        }
        throw new \Exception("Template '{$file}' was not found.");
    }

    function escape($text)
    {
        return \PFF\HtmlBuilder\Text::escape($text);
    }

    function e($text)
    {
        echo $this->escape($text);
    }

    function layout($name)
    {
        $this->_layout = $name;
    }

    function content()
    {
        return $this->_content;
    }
}
