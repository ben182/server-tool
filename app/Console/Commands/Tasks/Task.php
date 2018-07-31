<?php

namespace App\Console\Commands\Tasks;

abstract class Task
{
    public $oOptions;
    public $shell;

    abstract public function systemRequirements();
    abstract public function localRequirements();

    public function __construct($aOptions)
    {
        $this->oOptions = $aOptions;

        $this->shell = resolve('ShellTask');
    }
}
