<?php

namespace App\Console\Commands\Tasks;

use Illuminate\Support\Facades\Validator;

abstract class Taskmanager
{
    public $oOptions;
    public $aTasks;
    public $shell;

    abstract public function validate();

    public function __construct($aOptions = [])
    {
        $this->oOptions = (object) $aOptions;

        $this->shell = resolve('ShellTask');

        $validator = Validator::make($aOptions, $this->validate());

        if ($validator->fails()) {
            throw new \Exception($validator->errors());
        }
    }

    public function work()
    {
        foreach ($this->aTasks as $cTask) {
            $oTask = new $cTask($this->oOptions);

            $mSystemRequirements = $oTask->systemRequirements();

            if (gettype($mSystemRequirements) === 'boolean' && $mSystemRequirements === false) {
                echo $oTask->systemRequirementsErrorMessage ?? '';
                continue;
            }
            if (gettype($mSystemRequirements) === 'string') {
                if (!getInstallationConfigKey($mSystemRequirements)) {
                    echo $oTask->systemRequirementsErrorMessage ?? $oTask->sName . ' failed because ' . $mSystemRequirements . 'is not installed on your system.';
                    continue;
                }
            }
            if (! $oTask->localRequirements()) {
                continue;
            }

            echo $oTask->sName . '...';

            try {
                $oTask->handle();
            } catch(\Exception $e) {
                $this->shell->saveError($e);
            }

            echo($this->shell->hasErrors() ? 'fail' : 'done') . "\n";

            if ($this->shell->hasErrors()) {
                echo "I found {$this->shell->countErrors()} " . str_plural('error', $this->shell->countErrors()) . "\n";
                echo $this->shell->getErrors();
                $this->shell->flushErrors();
            }

            // TODO also output when errors occur?
            echo $this->shell->getOutput();
            $this->shell->flushOutput();
        }
    }
}
