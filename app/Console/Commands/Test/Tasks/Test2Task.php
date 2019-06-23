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
        // $this->errorBag->add('test');
        throw new \Exception('test');
        $this->command->line($this->bindings->key);
    }
}
