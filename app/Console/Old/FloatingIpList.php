<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use Illuminate\Console\Command;

class FloatingIpList extends ModCommand
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
        $floatingIps = collect(glob('/etc/network/interfaces.d/*.cfg'));
        $floatingIps = $floatingIps->map(function ($file) {
            return str_replace('.cfg', '', basename($file));
        })->filter(function ($file) {
            return is_sha1($file);
        })->map(function ($file) {
            $output = shell_exec('cat /etc/network/interfaces.d/' . $file . '.cfg 2>&1');

            if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $output, $ip_match)) {
                return $ip_match[0];
            }

            return null;
        })->filter()->values()->each(function ($ip) {
            $this->line($ip);
        });
    }
}
