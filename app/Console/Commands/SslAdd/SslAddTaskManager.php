<?php

namespace App\Console\Commands\SslAdd;

use App\Console\TaskManager;
use App\Console\Commands\SslAdd\Tasks\CreateSslCertificateTask;
use App\Rules\DomainExists;
use App\Helper\Domain;

class SslAddTaskManager extends TaskManager
{
    public $tasks = [
        CreateSslCertificateTask::class,
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
            'htaccess' => 'required|boolean',
        ];
    }
}
