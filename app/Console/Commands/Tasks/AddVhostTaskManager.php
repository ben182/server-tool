<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\AddVhost\ConfigureApacheConfiguration;
use App\Console\Commands\Tasks\AddVhost\ConfigureRedirects;
use App\Console\Commands\Tasks\AddVhost\CreateApacheConfiguration;
use App\Console\Commands\Tasks\AddVhost\CreateSslCertificate;
use App\Console\Commands\Tasks\Partials\Finish;
use Illuminate\Validation\Rule;

class AddVhostTaskManager extends Taskmanager
{
    public $aTasks = [
        CreateApacheConfiguration::class,
        ConfigureApacheConfiguration::class,
        CreateSslCertificate::class,
        ConfigureRedirects::class,
        Finish::class,
    ];

    public function validate()
    {
        return [
            'dev'      => 'required|boolean',
            'domain'   => 'required',
            'www'      => 'required|boolean',
            'ssl'      => 'boolean',
            'ssl_email'=> '',
            'htaccess' => [
                'required',
                Rule::in([
                    'Non SSL to SSL and www to non www',
                    'Non SSL to SSL',
                    'www to non www',
                    'Nothing',
                ]),
            ],
        ];
    }
}
