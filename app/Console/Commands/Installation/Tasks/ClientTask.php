<?php

namespace App\Console\Commands\Installation\Tasks;

use App\Console\Task;

class ClientTask extends Task
{
    public $name = 'Set up Apache Configuration';

    public function localRequirements()
    {
        return isset($this->options->master) && $this->options->master === false;
    }

    public function handle()
    {
        $this->shell->exec('sudo ufw allow 19999');
        $this->shell->exec('sudo ufw reload');

        $this->shell->replaceStringInFile('# allow connections from = localhost \*', 'allow connections from = localhost ' . $this->options->master_domain, '/etc/netdata/netdata.conf');

        $this->shell->service()->restart('netdata');

        $this->addConclusion('Execute this command on your master server:');
        $this->addConclusion('echo "' . app('stool-apache')->getOwnPublicIp() . ' ' . gethostname() . '" | sudo tee -a /etc/hosts');
    }
}
