<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\SubBaseTask;

class CreateApacheConfiguration extends SubBaseTask
{
    public $sName = 'Creating vHost';

    public function requirements()
    {
        return true;
    }

    public function handle()
    {
        try {
            copy(templates_path() . 'apache/vhost.conf', "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");

            replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'DOCUMENT_ROOT', $this->oOptions->domain);
        } catch (\Exception $e) {
            echo $e;
            return false;
        }

        return true;
    }
}
