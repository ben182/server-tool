<?php

namespace App\Console\Commands\WordpressInstall;

use App\Helper\Domain;
use App\Rules\DomainExists;
use Illuminate\Support\Str;
use App\Console\TaskManager;
use Illuminate\Validation\Rule;
use App\Console\Commands\WordpressInstall\Tasks\Database;
use App\Console\Commands\WordpressInstall\Tasks\WordpressConf;
use App\Console\Commands\WordpressInstall\Tasks\WordpressInit;
use App\Console\Commands\WordpressInstall\Tasks\LinkApplication;
use App\Console\Commands\WordpressInstall\Tasks\DownloadWordpress;

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

        $nameSlugged = Str::slug('wp-' . $this->options->name);
        $installDir  = $oDomain->getBaseFolder() . "/{$nameSlugged}";

        while ($this->shell->doesFolderExist($installDir)) {
            $nameSlugged = app('stool-increment')->increment($nameSlugged);
            $installDir  = $oDomain->getBaseFolder() . "/{$nameSlugged}";
        }

        $initials = app('stool-initials')->generate($this->options->name);
        $initials = strtolower($initials);

        return [
            'domain'          => $oDomain,
            'installationDir' => $installDir,
            'adminUrl' => $initials . '-admin',
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
