<?php

namespace App\Console\Commands\AddVhost\Tasks;

use App\Console\Task;

class ConfigureRedirectsTask extends Task
{
    public $name = 'Configuring htaccess';

    public function localRequirements()
    {
        return $this->options->htaccess && $this->options->htaccess !== 'Nothing' && ! $this->options->redirect;
    }

    public function handle()
    {
        $aFrom = [];
        switch ($this->options->htaccess) {
            case 'Non SSL to SSL and www to non www':

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path('apache/redirectSslAndWww.80.conf') . '\\n</VirtualHost>', $this->bindings->domain->getApacheSite());

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path('apache/redirectSslAndWww.443.conf') . '\\n</VirtualHost>', $this->bindings->domain->getApacheSslSite());

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

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path('apache/www_to_nonwww.htaccess') . '\\n</VirtualHost>', $this->bindings->domain->getApacheSite());

                $aFrom[] = [
                    'www.' . $this->options->domain,
                    $this->options->domain,
                ];

                break;

            case 'Non SSL to SSL':

                $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path('apache/nonSSL_to_SSL.htaccess') . '\\n</VirtualHost>', $this->bindings->domain->getApacheSite());

                $aFrom[] = [
                    'http://' . $this->options->domain,
                    'https://' . $this->options->domain,
                ];

                break;
        }

        $aStringFrom = [];
        foreach ($aFrom as $aEachFrom) {
            $aStringFrom[] = 'from ' . $aEachFrom[0] . ' to ' . $aEachFrom[1];
        }

        $this->addConclusion('Configured ' . (empty($aFrom) ? 'no ' : '') . 'redirect ' . (empty($aFrom) ? '' : implode(' and ', $aStringFrom)));
    }
}
