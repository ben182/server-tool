<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\MysqlCreate\CreateUserTask;
use App\Console\Commands\Tasks\MysqlCreate\MysqlCreateTask;
use App\Rules\MysqlDatabaseExistNot;

class MysqlCreateTaskManager extends Taskmanager
{
    public $aTasks = [
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
