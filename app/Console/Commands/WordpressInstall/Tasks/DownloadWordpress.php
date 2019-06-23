<?php

namespace App\Console\Commands\WordpressInstall\Tasks;

use App\Console\Task;

class DownloadWordpress extends Task
{
    public $name = 'Downloading Wordpress';

    public function handle()
    {
        $this->shell->exec("cd {$this->bindings->domain->getBaseFolder()} && curl -O https://wordpress.org/latest.tar.gz");
        $this->shell->exec("cd {$this->bindings->domain->getBaseFolder()} && tar xzvf latest.tar.gz");
        $this->shell->exec("cd {$this->bindings->domain->getBaseFolder()} && rm latest.tar.gz");
        $this->shell->exec("cd {$this->bindings->domain->getBaseFolder()} && mv wordpress {$this->bindings->installationDir}");

        $this->addConclusion("Downloaded and extracted wordpress to {$this->bindings->installationDir}");
    }
}
