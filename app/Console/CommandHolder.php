<?php

namespace App\Console;

use Illuminate\Console\Command;

class CommandHolder
{
    public static $command;

    public function __call($funName, $arguments)
    {
        return optional(self::$command)->$funName(...$arguments);
    }

    public static function setCommand(Command $command)
    {
        self::$command = $command;
    }

    public static function getCommand()
    {
        return new self();
    }
}
