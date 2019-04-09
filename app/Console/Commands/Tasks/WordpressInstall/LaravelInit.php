<?php

namespace App\Console\Commands\Tasks\WordpressInstall;

use App\Console\Commands\Tasks\Task;

class LaravelInit extends Task
{
    public $sName = 'Laravel Init';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->oOptions->laravel;
    }

    public function handle()
    {
        $this->shell->exec("composer install -d {$this->bindings->installationDir}");

        copy("{$this->bindings->installationDir}/.env.example", "{$this->bindings->installationDir}/.env");
        editEnvKey("{$this->bindings->installationDir}/.env", 'APP_URL', $this->bindings->domain->getFullUrl($this->oOptions->subDir));

        $this->shell->exec("cd {$this->bindings->installationDir} && php artisan key:generate");

        $this->addConclusion('Installed Composer Dependencies');
        $this->addConclusion('Copied .env');
        $this->addConclusion('Generated .env key');
    }
}
