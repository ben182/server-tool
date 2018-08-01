<?php

namespace App\Console\Commands\Tasks\TestTest;

use App\Console\Commands\Tasks\SubBaseTask;
use App\Console\Commands\Tasks\BaseTask;
use App\Console\Commands\Tasks\Task;
use App\Console\Commands\Tasks\TestTestTaskManager;

class TestTask extends Task
{
    public $sName = 'Test';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return true;
    }

    public function handle()
    {
        $this->addConclusion((int) $this->bindings->domain->doesExist());
    }
}
