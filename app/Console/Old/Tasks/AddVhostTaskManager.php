<?php

namespace App\Console\Commands\Tasks;

use Illuminate\Validation\Rule;
use App\Console\Commands\Tasks\Partials\ApacheFinishTask;
use App\Console\Commands\Tasks\AddVhost\ConfigureRedirectsTask;
use App\Console\Commands\Tasks\AddVhost\CreateSslCertificateTask;
use App\Console\Commands\Tasks\AddVhost\CreateApacheConfigurationTask;
use App\Console\Commands\Tasks\AddVhost\ConfigureApacheConfigurationTask;

class AddVhostTaskManager extends TaskManager
{
    public $tasks = [
        CreateApacheConfigurationTask::class,
        ConfigureApacheConfigurationTask::class,
        CreateSslCertificateTask::class,
        ConfigureRedirectsTask::class,
        ApacheFinishTask::class,
    ];

    public function validate()
    {
        return [
            'dev'      => 'required|boolean',
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
