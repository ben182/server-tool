<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\Partials\ApacheFinishTask;

class ApachePermissionsTaskManager extends Taskmanager
{
    public $aTasks = [
        ApacheFinishTask::class,
    ];

    public function validate()
    {
        return [];
    }
}
