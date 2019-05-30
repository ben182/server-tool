<?php

namespace App\Console\Commands\Tasks;

use App\Rules\FileExists;
use App\Console\Commands\Tasks\GitAutoDeploy\CreateRepositoryTask;

class GitAutoDeployTaskManager extends TaskManager
{
    public $tasks = [
        CreateRepositoryTask::class,
    ];

    public function validate()
    {
        return [
            'dir' => [
                'required',
                new FileExists,
            ],
            'branch' => 'required',
        ];
    }
}
