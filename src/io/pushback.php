<?php

namespace PFF\IO;

interface PushBackInputStream
{
    public function close();
    public function getc();
    public function ungetc($char);
}

class FileInputStream implements PushBackInputStream
{
    private $fd, $buffer = array();

    public function __construct($path, $mode)
    {
        $this->fd = fopen($path, $mode);
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close()
    {
        if ($this->fd) {
            fclose($this->fd);
            $this->fd = null;
        }
    }

    public function getc()
    {
        if (count($this->buffer)) {
            return array_shift($this->buffer);
        } else {
            return fgetc($this->fd);
        }
    }

    public function ungetc($char)
    {
        array_unshift($this->buffer, $char);
    }
}

class StringInputStream implements PushBackInputStream
{
    private $string, $position;

    public function __construct($string)
    {
        $this->string = $string;
        $this->position = 0;
    }

    public function close()
    {
        if ($this->string !== null)
            $this->string = null;
    }

    public function getc()
    {
        if ($this->position >= strlen($this->string))
            return false;
        else
            return $this->string[$this->position++];
    }

    public function ungetc($char)
    {
        --$this->position;
    }
}

