<?php

namespace App\Console\Commands\Installation\Tasks;

use App\Console\Task;
use App\Helper\Domain;
use App\Console\Commands\FloatingIps\FloatingIpCreateTaskManager;
use App\Setting;

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

        $user     = app('stool-password')->generate();
        $password = app('stool-password')->generate();
        $this->shell->exec('sudo htpasswd -c -b /etc/netdata/.htpasswd ' . $user . ' ' . $password);
        $this->shell->replaceStringInFile('NETDATA_HTACCESS_USERNAME', $user, base_path('config.json'));
        $this->shell->replaceStringInFile('NETDATA_HTACCESS_PASSWORD', $password, base_path('config.json'));

        // SSL
        if (! app('stool-check')->isIp($this->options->master_domain)) {
            if ($this->options->create_floating_ip) {
                FloatingIpCreateTaskManager::work([
                    'ip' => (new Domain($this->options->master_domain))->getARecord(),
                ]);
            }

            $sAdminEmail = Setting::getValue('admin_email');
            $this->shell->exec("sudo certbot --non-interactive --agree-tos --email $sAdminEmail --apache -d {$this->options->master_domain}");
            $this->shell->replaceStringInFile('</VirtualHost>', 'Include ' . templates_path('apache/nonSSL_to_SSL.htaccess') . '\\n</VirtualHost>', '/etc/apache2/sites-enabled/netdata.conf');
        }

        $this->shell->service()->restart('apache2');
    }
}
