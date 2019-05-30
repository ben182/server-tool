<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\Task;

class CreateApacheConfigurationTask extends Task
{
    public $name = 'Creating vHost';

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
        $this->shell->copy(templates_path('apache/vhost.conf'), "/etc/apache2/sites-available/{$this->options->domain}.conf");

        $this->addConclusion('Created vHost for domain: ' . $this->options->domain);
    }
}
