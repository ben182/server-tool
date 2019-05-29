<?php

namespace App\Console\Commands\AddVhost;

use App\Console\TaskManager;
use App\Console\Commands\Test\Tasks\TestTask;
use App\Console\Commands\Test\Tasks\Test2Task;

class TestTaskManager extends TaskManager
{
    public $tasks = [
        TestTask::class,
        Test2Task::class,
    ];

    public function validate()
    {
        return [
        ];
    }
}
