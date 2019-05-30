<?php

namespace App\Console\Commands\Tasks;

use Illuminate\Validation\Rule;
use App\Rules\MysqlDatabaseExist;
use App\Console\Commands\Tasks\MysqlBackup\MysqlBackupTask;

class MysqlBackupTaskManager extends TaskManager
{
    public $tasks = [
        MysqlBackupTask::class,
    ];

    public function validate()
    {
        return [
            'allDatabases' => 'required|boolean',
            'database'     => [
                'required_if:allDatabases,false',
                new MysqlDatabaseExist,
            ],
            'storage'      => [
                'required',
                Rule::in([
                    'local',
                    'spaces',
                ]),

            ],
            'cronjob' => 'required|boolean',
        ];
    }
}
