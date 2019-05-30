<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Setting;
use App\Console\Commands\Tasks\Task;

class CreateSslCertificateTask extends Task
{
    public $name                    = 'Setting up SSL';
    public $systemRequirementsError = '';

    public function systemRequirements()
    {
        return getInstallationConfigKey('certbot');
    }

    public function localRequirements()
    {
        return  $this->options->ssl;
    }

    public function handle()
    {
        $sAdminEmail = Setting::where('key', 'admin_email')->value('value');

        $this->shell->exec("sudo certbot --non-interactive --agree-tos --email $sAdminEmail --apache -d {$this->options->domain}" . ($this->options->www ? " -d www.{$this->options->domain}" : '') . ($this->options->dev ? ' --staging' : ''));

        $this->addConclusion('Provisioned SSL certificate for https://' . $this->options->domain . ($this->options->www ? " and https://www.{$this->options->domain}" : ''));
        $this->addConclusion("Check your SSL installation on https://www.ssllabs.com/ssltest/analyze.html?d={$this->options->domain}");
    }
}
