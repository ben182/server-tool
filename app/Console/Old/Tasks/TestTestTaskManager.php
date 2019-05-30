<?php

namespace App\Console\Commands\Tasks;

use App\Helper\Domain;
use App\Console\Commands\Tasks\TestTest\TestTask;
use App\Console\Commands\Tasks\TestTest\TestTask2;

class TestTestTaskManager extends TaskManager
{
    public $tasks = [
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
