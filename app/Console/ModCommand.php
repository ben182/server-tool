<?php

namespace App\Console;

use Illuminate\Console\Command;

class ModCommand extends Command
{
    public static $aReturn;

    public $shell;

    public function __construct()
    {
        parent::__construct();
        $this->shell = resolve('Shell');

        // add the debug option to the default options
        $this->getDefinition()->addOption(new \Symfony\Component\Console\Input\InputOption(
            'debug',
            'd',
            null,
            'Display all shell outputs'
        ));
    }

    public function handle() {
        $shell = resolve('ShellTask');
        if ($this->option('debug') === true) {
            $shell->outputEveryCommand();
        }
    }

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

    public function booleanOption($sOption, $sFallbackConfirm, $iDefault = 0)
    {
        return $this->option($sOption) === true ? true : $this->confirm($sFallbackConfirm, $iDefault);
    }

    public function stringOption($sOption, $sFallbackString)
    {
        return $this->option($sOption) ?? $this->ask($sFallbackString);
    }

    public function choiceOption($sOption, $sFallbackString, $aAllowedValues)
    {
        $sValue = $this->option($sOption) ?? $this->choice($sFallbackString, $aAllowedValues, 0);
        if (! in_array($sValue, $aAllowedValues)) {
            $this->abort("$sValue is not a valid choice for $sOption");
        }
        return $sValue;
    }

    /* public function choiceOption($sOption, $aAllowedValues)
    {
        $sValue = $this->option($sOption);
        if (!in_array($sValue, $aAllowedValues)) {
            $this->abort("$sValue is not a valid choice for $sOption");
        }
        return $sValue;
    } */
}
