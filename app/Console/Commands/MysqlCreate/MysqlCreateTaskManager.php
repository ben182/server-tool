<?php

namespace App\Console\Commands\MysqlCreate;

use App\Console\TaskManager;
use App\Console\Commands\AddVhost\Tasks\CreateSslCertificateTask;
use App\Console\Commands\MysqlCreate\Tasks\MysqlCreateTask;
use App\Console\Commands\MysqlCreate\Tasks\CreateUserTask;
use App\Rules\MysqlDatabaseExistNot;

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
