<?php

namespace App\Console\Commands\SslAdd\Tasks;

use App\Setting;
use App\Console\Task;

class CreateSslCertificateTask extends Task
{
    public $name = 'Setting up SSL';

    public function systemRequirements()
    {
        return 'certbot';
    }

    public function handle()
    {
        $sAdminEmail = Setting::getValue('admin_email');

        $www = $this->shell->isStringInFile($this->bindings->domain->getApacheSite(), 'ServerAlias www.');

        $this->shell->exec("sudo certbot --non-interactive --agree-tos --email $sAdminEmail --apache -d {$this->options->domain}" . ($www ? " -d www.{$this->options->domain}" : ''));

        $this->addConclusion('Provisioned SSL certificate for https://' . $this->options->domain . ($www ? " and https://www.{$this->options->domain}" : ''));

        if ($this->options->htaccess) {
            $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path('apache/nonSSL_to_SSL.htaccess') . '\\n</VirtualHost>', $this->bindings->domain->getApacheSite());

            $this->addConclusion('Configured redirect from http://' . $this->options->domain . ' to https://' . $this->options->domain);
        }

    }
}
