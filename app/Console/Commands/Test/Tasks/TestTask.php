<?php

namespace App\Console\Commands\Test\Tasks;

use App\Console\Task;

class TestTask extends Task
{
    public $name = 'Setting up SSL';

    // public function systemRequirements()
    // {
    //     return false;
    // }

    // public function localRequirements()
    // {
    //     return false;
    // }

    public function handle()
    {
        $this->addCustomBinding('key', 'value');


        $this->command->line($this->bindings->key);
        // $this->errorBag->add('test');
    }
}
