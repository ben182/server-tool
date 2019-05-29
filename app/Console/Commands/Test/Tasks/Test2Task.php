<?php

namespace App\Console\Commands\Test\Tasks;

use App\Console\Task;

class Test2Task extends Task
{
    public $name = 'Setting up SSL2';

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


        $this->command->line($this->bindings->key);
        // $this->errorBag->add('test');
    }
}
