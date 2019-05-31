<?php

namespace App\Console\Commands\Tasks\TestTest;

use App\Console\Task;

class TestTask extends Task
{
    public $name = 'Test';

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
