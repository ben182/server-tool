<?php

namespace App\Console\Commands\Tasks;

use Illuminate\Validation\Rule;
use App\Rules\DomainExists;
use App\Helper\Domain;
use App\Console\Commands\Tasks\ApplicationInstall\CloneRepository;
use App\Console\Commands\Tasks\ApplicationInstall\LinkApplication;
use App\Console\Commands\Tasks\ApplicationInstall\LaravelInit;
use App\Console\Commands\Tasks\ApplicationInstall\LaravelDatabase;
use App\Console\Commands\Tasks\ApplicationInstall\LaravelMigrate;
use App\Console\Commands\Tasks\ApplicationInstall\LaravelCronjob;
use App\Console\Commands\Tasks\ApplicationInstall\ComposerInstall;
use App\Console\Commands\Tasks\ApplicationInstall\NpmInstall;
use App\Console\Commands\Tasks\ApplicationInstall\GitPostPullHook;
use App\Console\Commands\Tasks\ApplicationInstall\Gad;
use App\Console\Commands\Tasks\Partials\ApacheFinishTask;


class ApplicationInstallTaskManager extends Taskmanager
{
    public $aTasks = [
        CloneRepository::class,
        LinkApplication::class,
        ComposerInstall::class,
        LaravelInit::class,
        LaravelDatabase::class,
        LaravelMigrate::class,
        LaravelCronjob::class,
        NpmInstall::class,
        GitPostPullHook::class,
        Gad::class,
        ApacheFinishTask::class,
    ];

    public function addVariableBinding() : array {
        $sGitName = getStringBetween($this->oOptions->git, '/', '.git');
        $oDomain = new Domain($this->oOptions->domain);

        return [
            'domain' => $oDomain,
            'installationDir' => "/var/www/{$this->oOptions->domain}/$sGitName",
            'htmlDir' => $oDomain->getHtmlFolder(),
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
            'directoryOrSymlink' => [
                'required',
                Rule::in([
                    'directory',
                    'symlink',
                ]),
            ],
            'symlinkSourceDir' => 'required_if:directoryOrSymlink,symlink',
            'git' => 'required',
            'branch' => 'required',
            'composerInstall' => 'required_if:laravel,false|boolean',
            'laravel' => 'required|boolean',
            'laravel_createDatabase' => 'required_if:laravel,true|boolean',
            'laravel_database' => 'required_if:laravel,true|boolean',
            'laravel_migrate' => [
                'required_if:laravel,true',
                Rule::in([
                    'Migrate',
                    'Migrate & Seed',
                    'Nothing',
                ]),
            ],
            'laravel_cronjob' => 'required_if:laravel,true|boolean',
            'npmInstall' => 'required|boolean',
            'gitPostPullHook' => 'required|boolean',
            'gad' => 'required|boolean',
            'gad_hartReset' => 'required_if:gad,true|boolean',
        ];
    }
}
