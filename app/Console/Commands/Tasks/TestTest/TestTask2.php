<?php

namespace App\Console\Commands\Tasks\TestTest;

use App\Console\Commands\Tasks\Task;

class TestTask2 extends Task
{
    public $sName = 'Test2';

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
        $this->addConclusion('I created this2');
        $this->shell->echo($this->bindings->bla);
    }
}
