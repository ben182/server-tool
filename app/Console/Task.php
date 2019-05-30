<?php

namespace App\Console;

use App\Helper\Shell\Shell;
use Illuminate\Support\Collection;

abstract class Task
{
    public $name = '';
    public $options;
    public $bindings;
    public $customBindings = [];
    public $errorBag;
    public $conclusions = [];
    public $systemRequirementsErrorMessage;
    public $command;

    /**
     * @var \App\Helper\Shell\Shell
     */
    public $shell;

    public function __construct(object $aOptions, array $aVariableBinding, Collection $errorBag)
    {
        $this->options = $aOptions;

        $this->shell    = app(Shell::class);
        $this->command  = CommandHolder::getCommand();
        $this->errorBag = $errorBag;
        $this->systemRequirementsErrorMessage = $this->systemRequirementsErrorMessage ?: $this->name . ' failed because it did not passed the system requirements';

        $this->bindings = new \stdclass();
        foreach ($aVariableBinding as $key => $value) {
            $this->bindings->$key = $value;
        }
    }

    public function addConclusion($sItem)
    {
        $this->conclusions[] = $sItem;
    }

    public function addCustomBinding($sKey, $sItem)
    {
        $this->customBindings[$sKey] = $sItem;
        $this->bindings->$sKey = $sItem;
    }

    public function systemRequirements()
    {
        return true;
    }
    public function localRequirements()
    {
        return true;
    }
}
