<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\GitAutoDeployNotificationSlack\GitAutoDeployNotificationSlackTask;


class GitAutoDeployNotificationSlackTaskManager extends Taskmanager
{
    public $aTasks = [
        GitAutoDeployNotificationSlackTask::class,
    ];

    public function validate()
    {
        return [
            'public_id' => 'required',
            'channel' => 'required',
        ];
    }
}
