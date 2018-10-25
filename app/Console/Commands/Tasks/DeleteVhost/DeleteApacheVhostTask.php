<?php

namespace App\Console\Commands\Tasks\DeleteVhost;

use App\Console\Commands\Tasks\Task;

class DeleteApacheVhostTask extends Task
{
    public $sName = 'Deleting Apache vHost';

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
        $this->shell->exec("sudo a2dissite {$this->oOptions->domain}.conf -q");
        $this->shell->removeFile("/etc/apache2/sites-available/{$this->oOptions->domain}.conf");

        if ($this->bindings->domain->isSSL()) {
            $this->shell->exec("sudo a2dissite {$this->oOptions->domain}-le-ssl.conf -q");
            $this->shell->removeFile("/etc/apache2/sites-available/{$this->oOptions->domain}-le-ssl.conf");
        }

        $this->addConclusion('Disabled and removed Apache vHost');
    }
}
