<?php

namespace App\Console\Commands\FloatingIps\Tasks;

use App\Console\Task;

class FloatingIpCreateTask extends Task
{
    public $name = 'Creating Floating IP';

    public function handle()
    {
        $encodedIp = sha1($this->options->ip);

        $file = '/etc/network/interfaces.d/' . $encodedIp . '.cfg';

        $floatingIps = collect(glob('/etc/network/interfaces.d/*.cfg'));
        $ethNo       = $floatingIps
        ->map(function ($file) {
            return str_replace('.cfg', '', basename($file));
        })
        ->filter(function ($file) {
            return app('stool-check')->isSha1($file);
        })
        ->map(function ($file) {
            $output = $this->shell->getFile('/etc/network/interfaces.d/' . $file . '.cfg');

            if (preg_match('/eth0:([\d]+)/', $output, $matches)) {
                return $matches[1];
            }
        })
        ->max();

        $this->shell->copy(templates_path('floating-ip.cfg'), $file);

        $this->shell->replaceStringInFile('your.float.ing.ip', $this->options->ip, $file);

        if ($ethNo) {
            $this->shell->replaceStringInFile('eth0:1', 'eth0:' . (++$ethNo), $file);
        }

        $this->shell->exec("sudo chmod -x $file");

        $this->shell->service()->restart('networking');

        $this->addConclusion('Successfully created floating ip');
    }
}
