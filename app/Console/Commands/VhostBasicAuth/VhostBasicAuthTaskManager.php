<?php

namespace App\Console\Commands\VhostBasicAuth;

use App\Helper\Domain;
use App\Console\TaskManager;
use Illuminate\Validation\Rule;
use App\Console\Commands\Partials\RestartApacheTask;
use App\Console\Commands\AddVhost\Tasks\ConfigureRedirectsTask;
use App\Console\Commands\AddVhost\Tasks\CreateSslCertificateTask;
use App\Console\Commands\AddVhost\Tasks\CreateApacheConfigurationTask;
use App\Console\Commands\AddVhost\Tasks\ConfigureApacheConfigurationTask;
use App\Console\Commands\VhostBasicAuth\Tasks\ModifyVhostTask;
use App\Rules\DomainExists;
use App\Console\Commands\VhostBasicAuth\Tasks\VhostBasicAuthTask;

class VhostBasicAuthTaskManager extends TaskManager
{
    public $tasks = [
        VhostBasicAuthTask::class,
        RestartApacheTask::class,
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
            'user'   => 'required',
            'password'   => 'required',
            'password_again' => 'required|same:password',
        ];
    }
}
