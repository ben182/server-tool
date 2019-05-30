<?php

namespace App\Console\Commands\Tasks;

use Illuminate\Validation\Rule;
use App\Console\Commands\Tasks\RedisBackup\RedisBackupTask;

class RedisBackupTaskManager extends TaskManager
{
    public $tasks = [
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
