<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\SubBaseTask;
use App\Console\Commands\Tasks\BaseTask;
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

        replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'DOCUMENT_ROOT', $this->oOptions->domain);
    }
}
