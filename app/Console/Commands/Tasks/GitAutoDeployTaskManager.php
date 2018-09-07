<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\GitAutoDeploy\CreateRepositoryTask;
use App\Rules\FileExists;

class GitAutoDeployTaskManager extends Taskmanager
{
    public $aTasks = [
        CreateRepositoryTask::class,
    ];

    public function validate()
    {
        return [
            'dir' => [
                'required',
                new FileExists
            ],
        ];
    }
}
