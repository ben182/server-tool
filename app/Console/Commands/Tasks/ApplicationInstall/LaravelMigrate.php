<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\Task;

class LaravelMigrate extends Task
{
    public $sName = 'Laravel Migrate';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->oOptions->laravel && $this->oOptions->laravel_migrate !== 'Nothing';
    }

    public function handle()
    {

        $this->shell->exec("cd {$this->bindings->installationDir} && sudo php artisan migrate");
        $this->addConclusion('Migrated the database');

        if ($this->oOptions->laravel_migrate == 'Migrate & Seed') {
            $this->shell->exec("cd {$this->bindings->installationDir} && sudo php artisan db:seed");
            $this->addConclusion('Seeded the database');
        }

    }
}
