<?php

namespace App\Console\Commands\FloatingIps;

use App\Helper\Check;
use App\Console\Command;
use App\Helper\FloatingIp;

class FloatingIpDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'floatingip:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $check;
    protected $floatingIp;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Check $check, FloatingIp $floatingIp)
    {
        parent::__construct();
        $this->check = $check;
        $this->floatingIp = $floatingIp;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ip = $this->choice('IP?', $this->floatingIp->getAllIps()->toArray());

        $encodedIp = sha1($ip);

        $file = '/etc/network/interfaces.d/' . $encodedIp . '.cfg';

        $this->shell->removeFile($file);

        $this->shell->service()->restart('networking');

        $this->info('Successfully deleted floating ip');
    }
}
