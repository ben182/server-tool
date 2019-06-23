<?php

namespace App\Console\Commands\MysqlCreate;

use App\Console\TaskManager;
use App\Console\Commands\MysqlCreate\Tasks\CreateUserTask;
use App\Console\Commands\MysqlCreate\Tasks\MysqlCreateTask;

class MysqlCreateTaskManager extends TaskManager
{
    public $tasks = [
        MysqlCreateTask::class,
        CreateUserTask::class,
    ];

    public function validate()
    {
        return [
            'database'         => [
                'required',
            ],
            'newUserAndAccess' => 'required|boolean',
        ];
    }
}
