<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\SnapshotBackupSetup\SaveDoTokenTask;
use App\Console\Commands\Tasks\SnapshotBackupSetup\InstallDependenciesTask;
use App\Console\Commands\Tasks\SnapshotBackupSetup\InstallCronjobTask;

class SnapshotBackupSetupTaskManager extends Taskmanager
{
    public $aTasks = [
        SaveDoTokenTask::class,
        InstallDependenciesTask::class,
        InstallCronjobTask::class,
    ];

    public function validate()
    {
        return [
            'doToken' => 'required',
            'keep' => 'required|integer',
        ];
    }
}
