<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\SnapshotBackup\SnapshotBackupTask;

class SnapshotBackupTaskManager extends TaskManager
{
    public $tasks = [
        SnapshotBackupTask::class,
    ];

    public function validate()
    {
        return [
            'keep' => 'required',
        ];
    }
}
