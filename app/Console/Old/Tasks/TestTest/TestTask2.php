<?php

namespace App\Console\Commands\Tasks\TestTest;

use App\Console\Task;

class TestTask2 extends Task
{
    public $name = 'Test2';

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
        $this->shell->environment()->save('test', $this->bindings->bla);
    }
}
