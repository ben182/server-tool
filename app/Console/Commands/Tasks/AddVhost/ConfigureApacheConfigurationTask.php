<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\Task;
use App\Setting;

class ConfigureApacheConfigurationTask extends Task
{
    public $sName = 'Configuring vHost';

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
        if ($this->oOptions->redirect) {
            replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'DocumentRoot /var/www/DOCUMENT_ROOT/html', "RedirectMatch permanent ^/(.*)$ {$this->oOptions->redirect_to}");
        }

        replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'DOCUMENT_ROOT', $this->oOptions->domain);

        if (! $this->oOptions->www) {
            replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'ServerAlias www.SERVER_NAME', '');
        }

        replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'SERVER_NAME', $this->oOptions->domain);
        replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'NAME', $this->oOptions->domain);

        replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", 'webmaster@localhost', Setting::where('key', 'admin_email')->value('value'));

        $this->shell->exec("sudo a2ensite {$this->oOptions->domain}.conf -q");

        $sFolder = "/var/www/{$this->oOptions->domain}/html";

        if (! file_exists($sFolder)) {
            mkdir($sFolder, 755, true);
        }

        $this->addConclusion('Configured www alias');
        $this->addConclusion('Created html folder in ' . $sFolder);
    }
}
