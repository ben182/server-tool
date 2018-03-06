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

    public function getReturn()
    {
        $aOptions = $this->options();
        if (isset($aOptions['nooutput'])) {
            if ($aOptions['nooutput'] === true) {
                return;
            }
        }

        return implode("\n", ModCommand::$aReturn) . "\n";
    }
}
