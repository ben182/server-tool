<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\Task;

class CreateApacheConfigurationTask extends Task
{
    public $sName = 'Creating vHost';

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
        copy(templates_path() . 'apache/vhost.conf', "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");

        $this->addConclusion('Created vHost for domain: ' . $this->oOptions->domain);
    }
}
