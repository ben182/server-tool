<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\TestTest\TestTask;
use App\Console\Commands\Tasks\TestTest\TestTask2;
use App\Helper\Domain;

class TestTestTaskManager extends Taskmanager
{
    public $aTasks = [
        TestTask::class,
        TestTask2::class,
    ];

    public function addVariableBinding() : array
    {
        return [
            'domain' => new Domain('test'),
        ];
    }

    public function validate()
    {
        return [];
    }
}
