<?php

namespace App\Console\Commands\Tasks;

use Illuminate\Support\Facades\Validator;

abstract class Taskmanager
{
    public $oOptions;
    public $aTasks;

    abstract public function validate();

    public function __construct($aOptions)
    {
        $this->oOptions = (object) $aOptions;

        $validator = Validator::make($aOptions, $this->validate());

        if ($validator->fails()) {
            throw new \Exception($validator->errors());
        }
    }

    public function work()
    {
        foreach ($this->aTasks as $cTask) {
            $oTask = new $cTask($this->oOptions);

            if (! $oTask->requirements()) {
                continue;
            }

            echo $oTask->sName . '...';
            echo($oTask->handle() ? 'done' : 'fail') . "\n";
        }
    }
}
