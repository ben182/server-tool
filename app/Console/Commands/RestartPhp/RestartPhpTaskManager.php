<?php

namespace App\Console\Commands\RestartPhp;

use App\Console\TaskManager;
use App\Console\Commands\Tasks\RefreshPhp\RestartPhpTask;

class RestartPhpTaskManager extends TaskManager
{
    public $tasks = [
        RestartPhpTask::class,
    ];
}
