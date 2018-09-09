<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\Task;
use App\Setting;

class CreateSslCertificateTask extends Task
{
    public $sName = 'Setting up SSL';
    public $systemRequirementsError = '';

    public function systemRequirements()
    {
        return getInstallationConfigKey('certbot');
    }

    public function localRequirements()
    {
        return  $this->oOptions->ssl;
    }

    public function handle()
    {
        $sAdminEmail = Setting::where('key', 'admin_email')->value('value');

        $this->shell->exec("certbot --non-interactive --agree-tos --email $sAdminEmail --apache -d {$this->oOptions->domain}" . ($this->oOptions->www ? " -d www.{$this->oOptions->domain}" : '') . ($this->oOptions->dev ? ' --staging' : ''));

        $this->addConclusion('Provisioned SSL certificate for https://' . $this->oOptions->domain . ($this->oOptions->www ? " and https://www.{$this->oOptions->domain}" : ''));
        $this->addConclusion("Check your SSL installation on https://www.ssllabs.com/ssltest/analyze.html?d={$this->oOptions->domain}");
    }
}