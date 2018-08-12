<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\RedisBackup\RedisBackupTask;
use Illuminate\Validation\Rule;

class RedisBackupTaskManager extends Taskmanager
{
    public $aTasks = [
        RedisBackupTask::class,
    ];

    public function validate()
    {
        return [
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
