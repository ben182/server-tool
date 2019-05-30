<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\Task;

class NpmInstall extends Task
{
    public $name = 'NPM Install';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->options->npmInstall;
    }

    public function handle()
    {
        $this->shell->exec("cd {$this->bindings->installationDir} && npm install");
    }
}
