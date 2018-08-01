<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\Task;

class ComposerInstall extends Task
{
    public $sName = 'Composer Install';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return !$this->oOptions->laravel && $this->oOptions->composerInstall;
    }

    public function handle()
    {
        $this->shell->exec("composer install -d {$this->bindings->installationDir}");

        $this->addConclusion('Installed Composer Dependencies');
    }
}
