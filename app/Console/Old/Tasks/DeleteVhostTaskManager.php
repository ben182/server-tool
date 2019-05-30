<?php

namespace App\Console\Commands\Tasks;

use App\Helper\Domain;
use App\Rules\DomainExists;
use App\Console\Commands\Tasks\Partials\ApacheFinishTask;
use App\Console\Commands\Tasks\DeleteVhost\DeleteApacheVhostTask;
use App\Console\Commands\Tasks\DeleteVhost\DeleteDomainFolderTask;

class DeleteVhostTaskManager extends TaskManager
{
    public $tasks = [
        DeleteApacheVhostTask::class,
        DeleteDomainFolderTask::class,
        ApacheFinishTask::class,
    ];

    public function addVariableBinding() : array
    {
        return [
            'domain' => new Domain($this->options->domain),
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
