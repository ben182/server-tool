<?php

namespace App\Console\Commands\Tasks;

use App\Rules\FileExists;
use App\Console\Commands\Tasks\Partials\CreateRepositoryTask;


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
            'hardreset' => 'required|boolean',
        ];
    }
}
