<?php

namespace App\Console\Commands\Tasks\FloatingIpCreate;

use App\Console\Commands\Tasks\Task;

class FloatingIpCreate extends Task
{
    public $sName = 'Setting up Floating IP';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return true;
    }

    public function handle()
    {
        $encodedIp = sha1($this->oOptions->ip);

        $file = '/etc/network/interfaces.d/' . $encodedIp . '.cfg';

        $this->shell->copy(templates_path('floating-ip.cfg'), $file);

        $this->shell->replaceStringInFile('your.float.ing.ip', $this->oOptions->ip, $file);

        $this->shell->exec('sudo service networking restart');
    }
}
