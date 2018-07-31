<?php

namespace App\Console\Commands\Tasks;

abstract class BaseTask
{
    public $oOptions;

    public function __construct($aOptions)
    {
        $this->oOptions = $aOptions;
    }
}
