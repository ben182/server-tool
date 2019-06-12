<?php

namespace App\Console\Commands\FloatingIps;

use App\Console\TaskManager;
use App\Console\Commands\DeamonCreate\Tasks\DeamonCreateTask;
use App\Console\Commands\FloatingIps\Tasks\FloatingIpCreateTask;

class FloatingIpCreateTaskManager extends TaskManager
{
    public $tasks = [
        FloatingIpCreateTask::class,
    ];

    public function validate()
    {
        return [
            'ip' => 'required|ip',
        ];
    }
}
