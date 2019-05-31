<?php

namespace App\Console\Commands\Tasks\DeleteVhost;

use App\Console\Task;

class DeleteApacheVhostTask extends Task
{
    public $name = 'Deleting Apache vHost';

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
        $this->shell->exec("sudo a2dissite {$this->options->domain}.conf -q");
        $this->shell->removeFile("/etc/apache2/sites-available/{$this->options->domain}.conf");

        if ($this->bindings->domain->isSSL()) {
            $this->shell->exec("sudo a2dissite {$this->options->domain}-le-ssl.conf -q");
            $this->shell->removeFile("/etc/apache2/sites-available/{$this->options->domain}-le-ssl.conf");
        }

        $this->addConclusion('Disabled and removed Apache vHost');
    }
}
