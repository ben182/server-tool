<?php

namespace App\Console\Commands\AddVhost;

use App\Console\TaskManager;
use Illuminate\Validation\Rule;
use App\Console\Commands\Tasks\Partials\ApacheFinishTask;
use App\Console\Commands\AddVhost\Tasks\ConfigureRedirectsTask;
use App\Console\Commands\AddVhost\Tasks\CreateSslCertificateTask;
use App\Console\Commands\AddVhost\Tasks\CreateApacheConfigurationTask;
use App\Console\Commands\AddVhost\Tasks\ConfigureApacheConfigurationTask;
use App\Helper\Domain;

class AddVhostTaskManager extends TaskManager
{
    public $aTasks = [
        CreateApacheConfigurationTask::class,
        ConfigureApacheConfigurationTask::class,
        CreateSslCertificateTask::class,
        ConfigureRedirectsTask::class,
        ApacheFinishTask::class, // TODO: not here yet
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
            'domain'   => 'required',
            'www'      => 'required|boolean',
            'ssl'      => 'required|boolean',
            'htaccess' => [
                'required_if:redirect,false',
                Rule::in([
                    'Non SSL to SSL and www to non www',
                    'Non SSL to SSL',
                    'www to non www',
                    'Nothing',
                ]),
            ],
            'redirect'    => 'required|boolean',
            'redirect_to' => 'required_if:redirect,true',
        ];
    }
}
