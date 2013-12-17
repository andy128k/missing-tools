<?php

namespace PFF;

abstract class CLI
{
    private function getArgs()
    {
        global $argv;
        if (is_array($argv))
            return $argv;
        if (@is_array($_SERVER['argv']))
            return $_SERVER['argv'];
        throw new Exception("Could not read command arguments (register_argc_argv=Off?)");
    }

    protected abstract function commands();

    public function printUsage($name)
    {
        echo "Usage: $name <command> [parameter...]\n\n";
        echo "Available commands:\n\n";

        $cmds = $this->commands();

        $max_len = 0;
        foreach ($cmds as $cmd) {
            list($key, $method, $description) = $cmd;
            $max_len = max($max_len, strlen($key));
        }

        $pad = floor(($max_len + 11) / 8) * 8;
        foreach ($cmds as $cmd) {
            list($key, $method, $description) = $cmd;
            echo "    ".str_pad($key, $pad).$description."\n";
        }
        echo "\n";
    }

    public function execute()
    {
        $args = $this->getArgs();

        if (count($args) <= 1) {
            $this->printUsage($args[0]);
            return 1;
        }

        $command = $args[1];
        $command_args = array_slice($args, 2);

        foreach ($this->commands() as $cmd) {
            list($key, $method, $description) = $cmd;
            if ($key === $command) {
                if (method_exists($this, $method)) {
                    return call_user_func_array(array($this, $method), $command_args);
                } else {
                    echo "Method $method is not defined.\n";
                    return 1;
                }
            }
        }

        echo "Unknown command.\n";
        return 1;
    }

    public static function run()
    {
        if (php_sapi_name() === 'cli') {
            $cli = new static;
            $code = $cli->execute();
            exit($code);
        }
    }
}

