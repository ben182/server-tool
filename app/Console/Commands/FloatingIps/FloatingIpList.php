<?php

namespace App\Console\Commands\FloatingIps;

use App\Helper\Check;
use App\Console\Command;
use App\Helper\FloatingIp;

class FloatingIpList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'floatingip:list';

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

        $this->check      = $check;
        $this->floatingIp = $floatingIp;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this
        ->floatingIp
        ->getAllIps()
        ->each(function ($ip) {
            $this->line($ip);
        });
    }
}
