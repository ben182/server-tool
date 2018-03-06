<?php

namespace App\Console;

use Illuminate\Console\Command;

class ModCommand extends Command
{
    public static $aReturn;

    public static function addToReturn($sMessage)
    {
        $this->aReturn[] = $sMessage;
    }

    public static function getReturn()
    {
        return implode("\n", $this->aReturn) . "\n";
    }
}
