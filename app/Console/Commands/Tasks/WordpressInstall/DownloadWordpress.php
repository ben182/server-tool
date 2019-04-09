<?php

namespace App\Console\Commands\Tasks\WordpressInstall;

use App\Console\Commands\Tasks\Task;

class DownloadWordpress extends Task
{
    public $sName = 'Downloading Wordpress';

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
        $this->shell->exec("cd {$this->bindings->domain->getBaseFolder()} && curl -O https://wordpress.org/latest.tar.gz");
        $this->shell->exec("cd {$this->bindings->domain->getBaseFolder()} && tar xzvf latest.tar.gz");
        $this->shell->exec("cd {$this->bindings->domain->getBaseFolder()} && rm latest.tar.gz");
        $this->shell->exec("cd {$this->bindings->domain->getBaseFolder()} && mv wordpress {$this->oOptions->name}");

        $this->addConclusion("I downloaded and extracted wordpress to {$this->bindings->installationDir}");
    }
}
