<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\Partials\CreateRepositoryTask;
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
            'branch' => 'required',
            'reset'  => 'required|boolean',
        ];
    }
}
