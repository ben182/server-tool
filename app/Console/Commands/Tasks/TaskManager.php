<?php

namespace App\Console\Commands\Tasks;

use Illuminate\Support\Facades\Validator;

abstract class Taskmanager
{
    public $oOptions;
    public $aTasks = [];
    public $shell;

    public static $rootTaskManager;
    public static $aConclusions = [];

    public $aCustomBindings = [];

    abstract public function validate();

    public function __construct($aOptions = [])
    {
        $this->oOptions = (object) $aOptions;

        $this->shell = resolve('ShellTask');

        $validator = Validator::make($aOptions, $this->validate());

        if ($validator->fails()) {
            throw new \Exception($validator->errors()); // TODO pretty
        }

        if (! self::$rootTaskManager) {
            self::$rootTaskManager = get_class($this);
        }
    }

    public static function addConclusion($aItems)
    {
        self::$aConclusions[] = $aItems;
        self::$aConclusions = array_flatten(self::$aConclusions);
    }

    public static function printConclusions()
    {
        echo implode("\n", self::$aConclusions) . "\n" . (! empty(self::$aConclusions ? "\n" : ''));
    }

    public function addVariableBinding() : array
    {
        return [];
    }

    public function addBinding($aBindings)
    {
        $this->aCustomBindings = array_merge($this->aCustomBindings, $aBindings);
    }

    public function isRootManager()
    {
        return self::$rootTaskManager === get_class($this);
    }
    public function isNotRootManager()
    {
        return ! $this->isRootManager();
    }

    public function work()
    {
        foreach ($this->aTasks as $cTask) {
            $oTask = new $cTask($this->oOptions, array_merge($this->addVariableBinding(), $this->aCustomBindings));

            $mSystemRequirements = $oTask->systemRequirements();

            if (gettype($mSystemRequirements) === 'boolean' && $mSystemRequirements === false) {
                echo $oTask->systemRequirementsErrorMessage ?? '';
                continue;
            }
            if (gettype($mSystemRequirements) === 'string') {
                if (! getInstallationConfigKey($mSystemRequirements)) {
                    echo($oTask->systemRequirementsErrorMessage ?? $oTask->sName) . ' failed because ' . $mSystemRequirements . ' is not installed on your system.';
                    continue;
                }
            }
            if (! $oTask->localRequirements()) {
                continue;
            }

            if ($this->isRootManager()) {
                echo $oTask->sName . '...';
            }

            try {
                $oTask->handle();
            } catch (\Exception $e) {
                $this->shell->saveError($e);
            }

            if ($this->isRootManager()) {
                echo($this->shell->hasErrors() ? 'fail' : 'done') . "\n";
            }

            if ($this->shell->hasErrors()) {
                echo "I found {$this->shell->countErrors()} " . str_plural('error', $this->shell->countErrors()) . "\n";
                echo $this->shell->getErrors();
                $this->shell->flushErrors();
                continue;
            }

            $this->addBinding($oTask->customBindings);

            $this->addConclusion($oTask->aConclusions);

            // TODO also output when errors occur?
            echo $this->shell->getOutput();
            $this->shell->flushOutput();
        }

        if ($this->isNotRootManager()) {
            return;
        }
        $this->printConclusions();
    }
}
