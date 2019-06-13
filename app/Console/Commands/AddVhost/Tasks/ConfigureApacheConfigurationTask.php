<?php

namespace App\Console\Commands\AddVhost\Tasks;

use App\Setting;
use App\Console\Task;

class ConfigureApacheConfigurationTask extends Task
{
    public $name = 'Configuring vHost';

    public function handle()
    {
        if ($this->options->redirect) {
            $this->shell->replaceStringInFile('DocumentRoot /home/stool/DOCUMENT_ROOT/html', "RedirectMatch permanent ^/(.*)$ {$this->options->redirect_to}", $this->bindings->domain->getApacheAvailableSite());
        }

        $this->shell->replaceStringInFile('DOCUMENT_ROOT', $this->options->domain, $this->bindings->domain->getApacheAvailableSite());

        if (! $this->options->www) {
            $this->shell->replaceStringInFile('ServerAlias www.SERVER_NAME', '', $this->bindings->domain->getApacheAvailableSite());
        }

        $this->shell->replaceStringInFile('SERVER_NAME', $this->options->domain, $this->bindings->domain->getApacheAvailableSite());
        $this->shell->replaceStringInFile('NAME', $this->options->domain, $this->bindings->domain->getApacheAvailableSite());

        $this->shell->replaceStringInFile('webmaster@localhost', Setting::getValue('admin_email'), $this->bindings->domain->getApacheAvailableSite());

        $this->bindings->domain->createHtmlFolder();

        $this->addConclusion('Configured www alias');
        $this->addConclusion('Created html folder in ' . $this->bindings->domain->getHtmlFolder());
    }
}
