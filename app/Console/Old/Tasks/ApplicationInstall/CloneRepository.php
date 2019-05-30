<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\Task;

class CloneRepository extends Task
{
    public $name = 'Cloning Repository';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return true;
    }

    public function handle()
    {
        $this->shell->exec("cd {$this->bindings->domain->getBaseFolder()} && git clone -b {$this->options->branch} {$this->options->git}");
        $this->shell->exec("cd {$this->bindings->installationDir} && git config core.filemode false");

        $this->addConclusion("I cloned the repository to {$this->bindings->installationDir}");
    }
}
