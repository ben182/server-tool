<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\RedisBackup\RedisBackupTask;
use Illuminate\Validation\Rule;
use App\Console\Commands\Tasks\FloatingIpCreate\FloatingIpCreate;

class FloatingIpCreateTaskManager extends Taskmanager
{
    public $aTasks = [
        FloatingIpCreate::class,
    ];

    public function validate()
    {
        return [
            'ip' => 'required',
        ];
    }
}
