<?php

namespace App\Console;

use Illuminate\Console\Command;

class ModCommand extends Command
{
    public static $aReturn;

    public static function addToReturn($sMessage)
    {
        ModCommand::$aReturn[] = $sMessage;
    }

    public static function getReturn()
    {
        if ($this->option('quiet')) {
            return;
        }

        return implode("\n", ModCommand::$aReturn) . "\n";
    }
}
