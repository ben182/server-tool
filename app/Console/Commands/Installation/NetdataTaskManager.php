<?php

namespace App\Console\Commands\Installation;

use App\Console\TaskManager;
use App\Console\Commands\Partials\RestartPhpTask;
use App\Console\Commands\Installation\Tasks\SetUpNotificationsTask;
use App\Console\Commands\Installation\Tasks\EnableMysqlTask;
use App\Console\Commands\Installation\Tasks\StandaloneTask;
use App\Console\Commands\Installation\Tasks\MasterTask;
use App\Console\Commands\Installation\Tasks\ClientTask;

class NetdataTaskManager extends TaskManager
{
    public $tasks = [
        SetUpNotificationsTask::class,
        EnableMysqlTask::class,
        StandaloneTask::class,
        MasterTask::class,
        ClientTask::class,
    ];

    public function validate()
    {
        return [
            'standalone' => 'required|boolean',
            'master' => 'required_without:standalone|boolean',
            'master_domain' => 'required_without:standalone',
            'slack_webhook' => 'required',
        ];
    }
}
