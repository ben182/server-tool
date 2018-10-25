<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\Task;

class ConfigureRedirectsTask extends Task
{
    public $sName = 'Configuring htaccess';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->oOptions->htaccess && $this->oOptions->htaccess !== 'Nothing' && ! $this->oOptions->redirect;
    }

    public function handle()
    {
        $aFrom = [];
        switch ($this->oOptions->htaccess) {
            case 'Non SSL to SSL and www to non www':

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path() . 'apache/redirectSslAndWww.80.conf' . PHP_EOL . '</VirtualHost>', "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path() . 'apache/redirectSslAndWww.443.conf' . PHP_EOL . '</VirtualHost>', "/etc/apache2/sites-available/{$this->oOptions->domain}-le-ssl.conf");

                $aFrom[] = [
                    'http://' . $this->oOptions->domain,
                    'https://' . $this->oOptions->domain,
                ];
                $aFrom[] = [
                    'www.' . $this->oOptions->domain,
                    $this->oOptions->domain,
                ];

                break;

            case 'www to non www':

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path() . 'apache/www_to_nonwww.htaccess' . PHP_EOL . '</VirtualHost>', "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");

                $aFrom[] = [
                    'www.' . $this->oOptions->domain,
                    $this->oOptions->domain,
                ];

                break;

            case 'Non SSL to SSL':

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path() . 'apache/nonSSL_to_SSL.htaccess' . PHP_EOL . '</VirtualHost>', "/etc/apache2/sites-available/{$this->oOptions->domain}.conf");

                $aFrom[] = [
                    'http://' . $this->oOptions->domain,
                    'https://' . $this->oOptions->domain,
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
