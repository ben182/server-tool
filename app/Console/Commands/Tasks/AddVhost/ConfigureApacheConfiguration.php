<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\BaseTask;

class ConfigureApacheConfiguration extends BaseTask
{
    public $sName = 'Configuring vHost';

    public function requirements()
    {
        return true;
    }

    public function handle()
    {
        try {
            if (! $this->oOptions->www) {
                replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'ServerAlias www.SERVER_NAME', '');
            }

            replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'SERVER_NAME', $this->oOptions->domain);
            replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'NAME', $this->oOptions->domain);

            quietCommand("a2ensite {$this->oOptions->domain}.conf -q");

            if (! file_exists("/var/www/{$this->oOptions->domain}/html")) {
                mkdir("/var/www/{$this->oOptions->domain}/html", 755, true);
            }
        } catch (\Exception $e) {
            echo $e;
            return false;
        }

        return true;
    }
}
