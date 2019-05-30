<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\SnapshotBackupSetup\SaveDoTokenTask;
use App\Console\Commands\Tasks\SnapshotBackupSetup\InstallCronjobTask;
use App\Console\Commands\Tasks\SnapshotBackupSetup\InstallDependenciesTask;

class SnapshotBackupSetupTaskManager extends TaskManager
{
    public $tasks = [
        SaveDoTokenTask::class,
        InstallDependenciesTask::class,
        InstallCronjobTask::class,
    ];

    public function validate()
    {
        return [
            'doToken' => 'required',
            'keep'    => 'required|integer',
        ];
    }
}
