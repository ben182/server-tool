<?php

namespace App\Console\Commands\Tasks;

abstract class Task
{
    public $oOptions;
    public $shell;
    public $bindings;
    public $customBindings = [];

    public $aConclusions = [];

    public function __construct($aOptions, array $aVariableBinding)
    {
        $this->options = $aOptions;

        $this->shell = resolve('ShellTask');

        $this->bindings = new \stdclass();
        foreach ($aVariableBinding as $key => $value) {
            $this->bindings->$key = $value;
        }
    }

    public function addConclusion($sItem)
    {
        $this->aConclusions[] = $sItem;
    }

    public function addCustomBinding($sKey, $sItem)
    {
        $this->customBindings[$sKey] = $sItem;
    }

    abstract public function systemRequirements();
    abstract public function localRequirements();
}
