<?php

namespace App\Console\Commands\FloatingIps;

use App\Console\Command;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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


        if (preg_match('/eth0:([\d]+)/', file_get_contents($file), $matches)) {
            $ethNo = $matches[1];
        }

        $this->shell->replaceStringInFile('eth0:' . $ethNo, 'eth0:' . (++$ethNo), $file);

        $this->shell->exec("sudo chmod -x $file");

        $this->shell->service()->restart('networking');

        $this->info('Successfully created floating ip');
    }
}
