<?php

namespace App\Console\Commands\Installation\Tasks;

use App\Console\Task;

class StandaloneTask extends Task
{
    public $name = 'Set up Apache Configuration';

    public function localRequirements()
    {
        return $this->options->standalone;
    }

    public function handle()
    {
        $this->shell->copy(templates_path('netdata/apache/standalone.conf'), '/etc/apache2/sites-available/netdata.conf');
        $this->shell->replaceStringInFile('IP_HERE', app('stool-apache')->getOwnPublicIp(), '/etc/apache2/sites-available/netdata.conf');
        $this->shell->exec('sudo chmod -x /etc/apache2/sites-available/netdata.conf');
        $this->shell->exec('sudo a2ensite netdata.conf');

        $user     = app('stool-password')->generate();
        $password = app('stool-password')->generate();
        $this->shell->exec('sudo htpasswd -c -b /etc/netdata/.htpasswd ' . $user . ' ' . $password);
        $this->shell->replaceStringInFile('NETDATA_HTACCESS_USERNAME', $user, base_path('config.json'));
        $this->shell->replaceStringInFile('NETDATA_HTACCESS_PASSWORD', $password, base_path('config.json'));

        $this->shell->service()->restart('apache2');

        $this->shell->exec('sudo ufw allow 20000');
        $this->shell->exec('sudo ufw reload');
    }
}
