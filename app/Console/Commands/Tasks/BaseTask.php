<?php

namespace App\Console\Commands\Tasks;

use Illuminate\Support\Facades\Validator;

abstract class BaseTask
{
    public $oOptions;
    public $aTasks;

    abstract public function validate();

    public function __construct($aOptions)
    {
        $this->oOptions = (object) $aOptions;

        $validator = Validator::make($aOptions, $this->validate());

        if ($validator->fails()) {
            throw new \Exception('validation-error', $validator->errors());
        }
    }

    public function work()
    {
        foreach ($this->aTasks as $cTask) {
            $oTask = new $cTask;

            if (! $oTask->requirements()) {
                continue;
            }

            echo $oTask->sName . '...';
            echo($oTask->handle() ? 'done' : 'fail') . "\n";
        }
    }
}
