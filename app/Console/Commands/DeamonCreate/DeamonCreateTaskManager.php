<?php

namespace App\Console\Commands\DeamonCreate;

use App\Console\Commands\Tasks\CreateDeamon\CreateDeamonTask;
use App\Console\TaskManager;
use App\Console\Commands\DeamonCreate\Tasks\DeamonCreateTask;

class DeamonCreateTaskManager extends TaskManager
{
    public $tasks = [
        DeamonCreateTask::class,
    ];

    public function validate()
    {
        return [
            'name'    => 'required',
            'command' => 'required',
        ];
    }
}
