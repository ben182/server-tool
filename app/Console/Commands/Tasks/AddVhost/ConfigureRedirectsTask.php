<?php

namespace App\Console\Commands\Tasks\AddVhost;

use App\Console\Commands\Tasks\BaseTask;
use App\Console\Commands\Tasks\Task;

class ConfigureRedirectsTask extends Task
{
    public $sName = 'Configuring htaccess';

    public function requirements()
    {
        return $this->oOptions->htaccess;
    }

    public function handle()
    {
        switch ($this->oOptions->htaccess) {
            case 'Non SSL to SSL and www to non www':

                replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", '</VirtualHost>', 'Include ' . templates_path() . 'apache/redirectSslAndWww.80.conf' . PHP_EOL . '</VirtualHost>');

                replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}-le-ssl.conf", '</VirtualHost>', 'Include ' . templates_path() . 'apache/redirectSslAndWww.443.conf' . PHP_EOL . '</VirtualHost>');

                break;

            case 'www to non www':

                replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", '</VirtualHost>', 'Include ' . templates_path() . 'apache/www_to_nonwww.htaccess' . PHP_EOL . '</VirtualHost>');

                break;

            case 'Non SSL to SSL':

                replace_string_in_file("/etc/apache2/sites-available/{$this->oOptions->domain}.conf", '</VirtualHost>', 'Include ' . templates_path() . 'apache/nonSSL_to_SSL.htaccess' . PHP_EOL . '</VirtualHost>');

                break;

            default:
                break;
        }
    }
}
