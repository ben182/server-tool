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

        dump('handle');
        $this->shell->exec('pwd');


        $this->command->line($this->bindings->key);
        // $this->errorBag->add('test');
    }
}
