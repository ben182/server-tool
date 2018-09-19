<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\RefreshPhp\RefreshPhpTask;


class RefreshPhpTaskManager extends Taskmanager
{
    public $aTasks = [
        RefreshPhpTask::class,
    ];

    public function validate()
    {
        return [];
    }
}
