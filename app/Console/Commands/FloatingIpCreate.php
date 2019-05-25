<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\MysqlCreateTaskManager;
use App\Console\ModCommand;
use Illuminate\Console\Command;
use App\Console\Commands\Tasks\FloatingIpCreateTaskManager;

class FloatingIpCreate extends ModCommand
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
        parent::handle();

        $sIp = $this->ask('IP?');

        (new FloatingIpCreateTaskManager([
            'ip' => $sIp,
        ]))->work();
    }
}
