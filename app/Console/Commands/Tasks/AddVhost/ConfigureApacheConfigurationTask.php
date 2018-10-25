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
            $this->shell->replaceStringInFile('DocumentRoot /var/www/DOCUMENT_ROOT/html', "RedirectMatch permanent ^/(.*)$ {$this->oOptions->redirect_to}", "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");
        }

        $this->shell->replaceStringInFile('DOCUMENT_ROOT', $this->oOptions->domain, "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");

        if (! $this->oOptions->www) {
            $this->shell->replaceStringInFile('ServerAlias www.SERVER_NAME', '', "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");
        }

        $this->shell->replaceStringInFile('SERVER_NAME', $this->oOptions->domain, "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");
        $this->shell->replaceStringInFile('NAME', $this->oOptions->domain, "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");

        $this->shell->replaceStringInFile('webmaster@localhost', Setting::where('key', 'admin_email')->value('value'), "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");

        $this->shell->exec("sudo a2ensite {$this->oOptions->domain}.conf -q");

        $sFolder = "/var/www/{$this->oOptions->domain}/html";

        if (! file_exists($sFolder)) {
            mkdir($sFolder, 755, true);
        }

        $this->addConclusion('Configured www alias');
        $this->addConclusion('Created html folder in ' . $sFolder);
    }
}
