<?php

namespace App\Console\Commands\Tasks\TestTest;

use App\Console\Commands\Tasks\SubBaseTask;
use App\Console\Commands\Tasks\BaseTask;
use App\Console\Commands\Tasks\Task;

class TestTask extends Task
{
    public $sName = 'Test';

    public function requirements()
    {
        return true;
    }

    public function handle()
    {
        $this->shell->exec('dir');
        //$this->shell->echo('das Funktioniert wirklich gut!');
        //throw new \Exception("Error Processing Request");
    }
}
