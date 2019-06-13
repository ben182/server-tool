<?php

namespace App\Console\Commands\AddVhost\Tasks;

use App\Console\Task;

class CreateApacheConfigurationTask extends Task
{
    public $name = 'Creating vHost';

    public function handle()
    {
        $this->shell->copy(templates_path('apache/vhost.conf'), $this->bindings->domain->getApacheAvailableSite());

        $this->shell->exec('sudo ln -s ' . $this->bindings->domain->getApacheAvailableSite() . ' ' . $this->bindings->domain->getApacheSite());

        $this->addConclusion('Created vHost for domain: ' . $this->options->domain);
    }
}
