<?php

namespace App\Console\Commands\FloatingIps;

use App\Helper\Check;
use App\Console\Command;

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
        $this
        ->getAllIps()
        ->each(function ($ip) {
            $this->line($ip);
        });
    }

    public static function getAllIps() {
        $floatingIps = collect(glob('/etc/network/interfaces.d/*.cfg'));

        return $floatingIps
        ->map(function ($file) {
            return str_replace('.cfg', '', basename($file));
        })
        ->filter(function ($file) {
            return $this->check->isSha1($file);
        })
        ->map(function ($file) {
            $output = $this->shell->getFile('/etc/network/interfaces.d/' . $file . '.cfg');

            if ($ips = $this->check->getIps($output)) {
                return $ips[0];
            }

            return null;
        })
        ->filter()
        ->values();
    }
}
