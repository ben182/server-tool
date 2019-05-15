<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\ApplicationInstall\CloneRepository;
use App\Console\Commands\Tasks\ApplicationInstall\ComposerInstall;
use App\Console\Commands\Tasks\ApplicationInstall\Gad;
use App\Console\Commands\Tasks\ApplicationInstall\GitPostPullHook;
use App\Console\Commands\Tasks\ApplicationInstall\LaravelCronjob;
use App\Console\Commands\Tasks\ApplicationInstall\LaravelDatabase;
use App\Console\Commands\Tasks\ApplicationInstall\LaravelInit;
use App\Console\Commands\Tasks\WordpressInstall\LinkApplication;
use App\Console\Commands\Tasks\ApplicationInstall\NpmInstall;
use App\Console\Commands\Tasks\Partials\ApacheFinishTask;
use App\Helper\Domain;
use App\Rules\DomainExists;
use Illuminate\Validation\Rule;
use App\Console\Commands\Tasks\WordpressInstall\DownloadWordpress;
use App\Console\Commands\Tasks\WordpressInstall\Database;
use Illuminate\Support\Str;
use App\Console\Commands\Tasks\WordpressInstall\WordpressInit;
use App\Console\Commands\Tasks\WordpressInstall\WordpressConf;

class WordpressInstallTaskManager extends Taskmanager
{
    public $aTasks = [
        DownloadWordpress::class,
        WordpressInit::class,
        Database::class,
        WordpressConf::class,
        LinkApplication::class,
    ];

    public function addVariableBinding() : array
    {
        $oDomain = new Domain($this->oOptions->domain);

        $sNameSlugged = Str::slug($this->oOptions->name);

        return [
            'domain'          => $oDomain,
            'installationDir' => "/home/stool/{$this->oOptions->domain}/{$sNameSlugged}",
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
            'subDir' => 'required_if:rootOrSub,Sub',
            'name' => 'required',
            'installPlugins' => 'required|boolean',
            'pioneersConfig' => 'required|boolean',
        ];
    }
}
