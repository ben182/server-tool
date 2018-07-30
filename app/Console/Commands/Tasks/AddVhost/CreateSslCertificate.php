<?php

namespace App\Console\Commands\Tasks\AddVhost;

class CreateSslCertificate
{
    public $sName = 'Setting up SSL';

    public function requirements()
    {
        return getInstallationConfigKey('certbot') && $this->oOptions->ssl;
    }

    public function handle()
    {
        try {
            quietCommand("certbot --non-interactive --agree-tos --email {$this->oOptions->ssl_email} --apache -d {$this->oOptions->domain}" . ($this->oOptions->www ? " -d www.{$this->oOptions->domain}" : '') . ' --quiet' . ($this->oOptions->dev ? ' --staging' : ''));
        } catch (\Exception $e) {
            echo $e;
            return false;
        }

        //$this->line('Check your SSL installation on https://www.ssllabs.com/ssltest/analyze.html?d=' . $sDomain);
        // TODO Output
        return true;
    }
}
