<?php

namespace App\Console\Commands\Tasks;

abstract class SubBaseTask
{
    public $oOptions;

    public function __construct($aOptions)
    {
        $this->oOptions = $aOptions;
    }
}
