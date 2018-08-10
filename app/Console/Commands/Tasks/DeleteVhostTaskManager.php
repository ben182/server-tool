<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\DeleteVhost\DeleteApacheVhostTask;
use App\Console\Commands\Tasks\DeleteVhost\DeleteDomainFolderTask;
use App\Console\Commands\Tasks\Partials\ApacheFinishTask;
use App\Rules\DomainExists;

class DeleteVhostTaskManager extends Taskmanager
{
    public $aTasks = [
        DeleteApacheVhostTask::class,
        DeleteDomainFolderTask::class,
        ApacheFinishTask::class,
    ];

    public function validate()
    {
        return [
            'domain' => [
                'required',
                new DomainExists,
            ],
            'deleteDomainFolder' => 'required|boolean',
        ];
    }
}
