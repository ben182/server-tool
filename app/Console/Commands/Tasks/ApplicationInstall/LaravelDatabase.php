<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\Task;

class LaravelDatabase extends Task
{
    public $sName = 'Laravel Database';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->oOptions->laravel && $this->oOptions->laravel_database;
    }

    public function handle()
    {
        $sDatabaseName = $this->shell->mysql()->createDatabase($this->bindings->installationDir);
        $oUser = $this->shell->mysql()->createUser()->giveAccessToDatabase($sDatabaseName);

        editEnvKey("{$this->bindings->installationDir}/.env", 'DB_DATABASE', $sDatabaseName);
        editEnvKey("{$this->bindings->installationDir}/.env", 'DB_USERNAME', $oUser->getName());
        editEnvKey("{$this->bindings->installationDir}/.env", 'DB_PASSWORD', $oUser->getPassword());

        if ($this->oOptions->laravel_migrate != 'Nothing') {
            $this->shell->exec("cd {$this->bindings->installationDir} && sudo php artisan migrate");

            if ($this->oOptions->laravel_migrate == 'Migrate & Seed') {
                $this->shell->exec("cd {$this->bindings->installationDir} && sudo php artisan db:seed");
            }
        }

        $this->addConclusion('[DB]');
        $this->addConclusion('Database: ' . $sDatabaseName);
        $this->addConclusion('User: ' . $oUser->getName());
        $this->addConclusion('Password: ' . $oUser->getPassword());
    }
}