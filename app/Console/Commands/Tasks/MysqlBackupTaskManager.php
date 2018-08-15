<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\MysqlBackup\MysqlBackupTask;
use Illuminate\Validation\Rule;

class MysqlBackupTaskManager extends Taskmanager
{
    public $aTasks = [
        MysqlBackupTask::class,
    ];

    public function validate()
    {
        return [
            'allDatabases' => 'required|boolean',
            'database'     => 'required_if:allDatabases,false', // TODO: check if this is a real database
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
