<?php

namespace App\Console\Commands\FloatingIps;

use App\Helper\Check;
use App\Console\Command;

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
        $ip = $this->anticipate('IP?', FloatingIpList::getAllIps()->toArray());

        $encodedIp = sha1($ip);

        $file = '/etc/network/interfaces.d/' . $encodedIp . '.cfg';

        $this->shell->removeFile($file);

        $this->shell->service()->restart('networking');

        $this->info('Successfully deleted floating ip');
    }
}
