<?php

namespace App\Console\Commands\Tasks;

use App\Rules\MysqlDatabaseExistNot;
use App\Console\Commands\Tasks\MysqlCreate\CreateUserTask;
use App\Console\Commands\Tasks\MysqlCreate\MysqlCreateTask;

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
                new MysqlDatabaseExistNot,
            ],
            'newUserAndAccess' => 'required|boolean',
        ];
    }
}
