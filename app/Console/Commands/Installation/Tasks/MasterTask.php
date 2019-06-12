<?php

namespace App\Console\Commands\Installation\Tasks;

use App\Console\Task;

class MasterTask extends Task
{
    public $name = 'Set up Apache Configuration';

    public function localRequirements()
    {
        return isset($this->options->master) && $this->options->master === true;
    }

    public function handle()
    {
        $this->shell->copy(templates_path('netdata/apache/master.conf'), '/etc/apache2/sites-available/netdata.conf');
        $this->shell->replaceStringInFile('SERVERNAME_HERE', $this->options->master_domain, '/etc/apache2/sites-available/netdata.conf');
        $this->shell->exec('sudo chmod -x /etc/apache2/sites-available/netdata.conf');
        $this->shell->exec('sudo a2ensite netdata.conf');

        $user = app('stool-password')->generate();
        $password = app('stool-password')->generate();
        $this->shell->exec('sudo htpasswd -c -b /etc/netdata/.htpasswd ' . $user . ' ' . $password);
        $this->shell->replaceStringInFile('NETDATA_HTACCESS_USERNAME', $user, base_path('config.json'));
        $this->shell->replaceStringInFile('NETDATA_HTACCESS_PASSWORD', $password, base_path('config.json'));

        $this->shell->service()->restart('apache2');
    }
}
