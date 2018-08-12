<?php

namespace App\Console\Commands\Tasks\TestTest;

use App\Console\Commands\Tasks\Task;

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
        $this->addCustomBinding('bla', 'item');
    }
}
