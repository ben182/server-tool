<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\Partials\ApacheFinishTask;

class ApachePermissionsTaskManager extends TaskManager
{
    public $tasks = [
        ApacheFinishTask::class,
    ];

    public function validate()
    {
        return [];
    }
}
