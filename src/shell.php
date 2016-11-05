<?php

namespace PFF;

class Shell
{
    public static function call()
    {
        self::callv(func_get_args());
    }

    public static function callv(array $command)
    {
        $str = '';
        foreach ($command as $part) {
            $str .= escapeshellarg($part) . ' ';
        }
        $descriptorspec = array(
            0 => STDIN,
            1 => STDOUT,
            2 => STDERR
        );
        $process = proc_open($str, $descriptorspec, $pipes);
        proc_close($process);
    }
}
