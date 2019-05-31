<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Setting;
use App\Console\Task;

class ConfigureApacheConfigurationTask extends Task
{
    public $name = 'Configuring vHost';

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
        if ($this->options->redirect) {
            $this->shell->replaceStringInFile('DocumentRoot /home/stool/DOCUMENT_ROOT/html', "RedirectMatch permanent ^/(.*)$ {$this->options->redirect_to}", "/etc/apache2/sites-available/{$this->options->domain}.conf");
        }

        $this->shell->replaceStringInFile('DOCUMENT_ROOT', $this->options->domain, "/etc/apache2/sites-available/{$this->options->domain}.conf");

        if (! $this->options->www) {
            $this->shell->replaceStringInFile('ServerAlias www.SERVER_NAME', '', "/etc/apache2/sites-available/{$this->options->domain}.conf");
        }

        $this->shell->replaceStringInFile('SERVER_NAME', $this->options->domain, "/etc/apache2/sites-available/{$this->options->domain}.conf");
        $this->shell->replaceStringInFile('NAME', $this->options->domain, "/etc/apache2/sites-available/{$this->options->domain}.conf");

        $this->shell->replaceStringInFile('webmaster@localhost', Setting::where('key', 'admin_email')->value('value'), "/etc/apache2/sites-available/{$this->options->domain}.conf");

        $this->shell->exec("sudo a2ensite {$this->options->domain}.conf -q");

        $sFolder = "/home/stool/{$this->options->domain}/html";

        if (! file_exists($sFolder)) {
            mkdir($sFolder, 755, true);
        }

        $this->addConclusion('Configured www alias');
        $this->addConclusion('Created html folder in ' . $sFolder);
    }
}
