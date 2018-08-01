<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\Task;

class LaravelCronjob extends Task
{
    public $sName = 'Laravel Cronjob';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->oOptions->laravel && $this->oOptions->laravel_cronjob;
    }

    public function handle()
    {

        $this->shell->exec("crontab -l | { cat; echo \"* * * * * {$this->bindings->installationDir}/artisan schedule:run >> /dev/null 2>&1\"; } | crontab -");

    }
}
