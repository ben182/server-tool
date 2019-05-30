<?php

namespace App\Console\Commands\Tasks;

use App\Helper\Domain;
use App\Rules\DomainExists;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Console\Commands\Tasks\WordpressInstall\Database;
use App\Console\Commands\Tasks\WordpressInstall\WordpressConf;
use App\Console\Commands\Tasks\WordpressInstall\WordpressInit;
use App\Console\Commands\Tasks\WordpressInstall\LinkApplication;
use App\Console\Commands\Tasks\WordpressInstall\DownloadWordpress;

class WordpressInstallTaskManager extends TaskManager
{
    public $tasks = [
        DownloadWordpress::class,
        WordpressInit::class,
        Database::class,
        WordpressConf::class,
        LinkApplication::class,
    ];

    public function addVariableBinding() : array
    {
        $oDomain = new Domain($this->options->domain);

        $sNameSlugged = Str::slug($this->options->name);

        return [
            'domain'          => $oDomain,
            'installationDir' => "/home/stool/{$this->options->domain}/{$sNameSlugged}",
            'htmlDir'         => $oDomain->getHtmlFolder(),
        ];
    }

    public function validate()
    {
        return [
            'domain' => [
                'required',
                new DomainExists,
            ],
            'rootOrSub' => [
                'required',
                Rule::in([
                    'Root',
                    'Sub',
                ]),
            ],
            'subDir'         => 'required_if:rootOrSub,Sub',
            'name'           => 'required',
            'installPlugins' => 'required|boolean',
            'pioneersConfig' => 'required|boolean',
            'local'          => 'required|boolean',
        ];
    }
}
