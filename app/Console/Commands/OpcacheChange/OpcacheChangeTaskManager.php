<?php

namespace App\Console\Commands\OpcacheChange;

use App\Console\TaskManager;
use App\Console\Commands\Partials\RestartPhpTask;
use App\Console\Commands\OpcacheChange\Tasks\OpcacheChangeTask;

class OpcacheChangeTaskManager extends TaskManager
{
    public $tasks = [
        OpcacheChangeTask::class,
        RestartPhpTask::class,
    ];

    public function validate()
    {
        return [
            'mode' => 'required|boolean',
            'validateTimestamps' => 'boolean',
        ];
    }
}
