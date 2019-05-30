<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\GitAutoDeployNotificationSlack\GitAutoDeployNotificationSlackTask;

class GitAutoDeployNotificationSlackTaskManager extends TaskManager
{
    public $tasks = [
        GitAutoDeployNotificationSlackTask::class,
    ];

    public function validate()
    {
        return [
            'channel' => 'required',
        ];
    }
}
