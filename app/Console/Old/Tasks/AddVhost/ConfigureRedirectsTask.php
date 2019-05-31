<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Task;

class ConfigureRedirectsTask extends Task
{
    public $name = 'Configuring htaccess';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->options->htaccess && $this->options->htaccess !== 'Nothing' && ! $this->options->redirect;
    }

    public function handle()
    {
        $aFrom = [];
        switch ($this->options->htaccess) {
            case 'Non SSL to SSL and www to non www':

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path() . 'apache/redirectSslAndWww.80.conf\\n</VirtualHost>', "/etc/apache2/sites-available/{$this->options->domain}.conf");

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path() . 'apache/redirectSslAndWww.443.conf\\n</VirtualHost>', "/etc/apache2/sites-available/{$this->options->domain}-le-ssl.conf");

                $aFrom[] = [
                    'http://' . $this->options->domain,
                    'https://' . $this->options->domain,
                ];
                $aFrom[] = [
                    'www.' . $this->options->domain,
                    $this->options->domain,
                ];

                break;

            case 'www to non www':

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path() . 'apache/www_to_nonwww.htaccess\\n</VirtualHost>', "/etc/apache2/sites-available/{$this->options->domain}.conf");

                $aFrom[] = [
                    'www.' . $this->options->domain,
                    $this->options->domain,
                ];

                break;

            case 'Non SSL to SSL':

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path() . 'apache/nonSSL_to_SSL.htaccess\\n</VirtualHost>', "/etc/apache2/sites-available/{$this->options->domain}.conf");

                $aFrom[] = [
                    'http://' . $this->options->domain,
                    'https://' . $this->options->domain,
                ];

                break;

            default:
                break;
        }

        $aStringFrom = [];
        foreach ($aFrom as $aEachFrom) {
            $aStringFrom[] = 'from ' . $aEachFrom[0] . ' to ' . $aEachFrom[1];
        }

        $this->addConclusion('Configured ' . (empty($aFrom) ? 'no ' : '') . 'Redirect ' . (empty($aFrom) ? '' : implode(' and ', $aStringFrom)));
    }
}
