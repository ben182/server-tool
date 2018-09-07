<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\DeleteVhost\DeleteApacheVhostTask;
use App\Console\Commands\Tasks\DeleteVhost\DeleteDomainFolderTask;
use App\Console\Commands\Tasks\Partials\ApacheFinishTask;
use App\Helper\Domain;
use App\Rules\DomainExists;

class DeleteVhostTaskManager extends Taskmanager
{
    public $aTasks = [
        DeleteApacheVhostTask::class,
        DeleteDomainFolderTask::class,
        ApacheFinishTask::class,
    ];

    public function addVariableBinding() : array
    {
        return [
            'domain' => new Domain($this->oOptions->domain),
        ];
    }

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
