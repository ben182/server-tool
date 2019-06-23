<?php

namespace App\Console\Commands\AddVhost\Tasks;

use App\Setting;
use App\Console\Task;

class CreateSslCertificateTask extends Task
{
    public $name = 'Setting up SSL';

    public function localRequirements()
    {
        return  $this->options->ssl;
    }

    public function handle()
    {
        $sAdminEmail = Setting::getValue('admin_email');

        $this->shell->exec("sudo certbot --non-interactive --agree-tos --email $sAdminEmail --apache -d {$this->options->domain}" . ($this->options->www ? " -d www.{$this->options->domain}" : ''));

        $this->addConclusion('Provisioned SSL certificate for https://' . $this->options->domain . ($this->options->www ? " and https://www.{$this->options->domain}" : ''));
    }
}
