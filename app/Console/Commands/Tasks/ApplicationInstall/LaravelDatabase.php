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
        return $this->oOptions->laravel && $this->oOptions->laravel_createDatabase;
    }

    public function handle()
    {
        $sDatabaseName = createMysqlDatabase($this->bindings->installationDir);
        $aUserData = createMysqlUserAndGiveAccessToDatabase($sDatabaseName);

        editEnvKey("{$this->bindings->installationDir}/.env", 'DB_DATABASE', $sDatabaseName);
        editEnvKey("{$this->bindings->installationDir}/.env", 'DB_USERNAME', $aUserData['user']);
        editEnvKey("{$this->bindings->installationDir}/.env", 'DB_PASSWORD', $aUserData['password']);

        if ($this->oOptions->laravel_migrate != 'Nothing') {
            $this->shell->exec("cd {$this->bindings->installationDir} && sudo php artisan migrate");

            if ($this->oOptions->laravel_migrate == 'Migrate & Seed') {
                $this->shell->exec("cd {$this->bindings->installationDir} && sudo php artisan db:seed");
            }
        }

        $this->addConclusion('[DB]');
        $this->addConclusion('Database: ' . $sDatabaseName);
        $this->addConclusion('User: ' . $aUserData['user']);
        $this->addConclusion('Password: ' . $aUserData['password']);
    }
}
