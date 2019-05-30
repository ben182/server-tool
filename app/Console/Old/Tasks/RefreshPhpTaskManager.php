<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\RefreshPhp\RefreshPhpTask;

class RefreshPhpTaskManager extends TaskManager
{
    public $tasks = [
        RefreshPhpTask::class,
    ];

    public function validate()
    {
        return [];
    }
}
