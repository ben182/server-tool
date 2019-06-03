<?php

namespace App\Console\Commands\FloatingIps;

use App\Console\Command;
use App\Helper\Check;

class FloatingIpCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'floatingip:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $check;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Check $check)
    {
        parent::__construct();
        $this->check = $check;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ip = $this->ask('IP?');

        $encodedIp = sha1($ip);

        $file = '/etc/network/interfaces.d/' . $encodedIp . '.cfg';

        $this->shell->copy(templates_path('floating-ip.cfg'), $file);

        $this->shell->replaceStringInFile('your.float.ing.ip', $ip, $file);

        $floatingIps = collect(glob('/etc/network/interfaces.d/*.cfg'));
        $ethNo = $floatingIps
        ->map(function ($file) {
            return str_replace('.cfg', '', basename($file));
        })
        ->filter(function ($file) {
            return $this->check->isSha1($file);
        })
        ->map(function ($file) {
            $output = $this->shell->getFile('/etc/network/interfaces.d/' . $file . '.cfg');

            if (preg_match('/eth0:([\d]+)/', $output, $matches)) {
                return $matches[1];
            }
        })
        ->max();
        $this->shell->replaceStringInFile('eth0:1', 'eth0:' . (++$ethNo), $file);

        $this->shell->exec("sudo chmod -x $file");

        $this->shell->service()->restart('networking');

        $this->info('Successfully created floating ip');
    }
}
