<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Task;

class ComposerInstall extends Task
{
    public $name = 'Composer Install';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return ! $this->options->laravel && $this->options->composerInstall;
    }

    public function handle()
    {
        $this->shell->exec("composer install -d {$this->bindings->installationDir}");

        $this->addConclusion('Installed Composer Dependencies');
    }
}
