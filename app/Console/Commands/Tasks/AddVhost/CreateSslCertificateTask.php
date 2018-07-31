<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\SubBaseTask;
use App\Console\Commands\Tasks\BaseTask;
use App\Console\Commands\Tasks\Task;

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
        $this->shell->exec("certbot --non-interactive --agree-tos --email {$this->oOptions->ssl_email} --apache -d {$this->oOptions->domain}" . ($this->oOptions->www ? " -d www.{$this->oOptions->domain}" : '') . ($this->oOptions->dev ? ' --staging' : ''));

        $this->shell->echo("Check your SSL installation on https://www.ssllabs.com/ssltest/analyze.html?d={$this->oOptions->domain}");

        $this->addConclusion('Provisioned SSL certificate for https://' . $this->oOptions->domain . ($this->oOptions->www ? " and www.{$this->oOptions->domain}" : ''));
    }
}
