<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\ApplicationInstallTaskManager;
use App\Console\ModCommand;
use App\Console\Commands\Tasks\WordpressInstallTaskManager;

class Update extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update';

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

        echo shell_exec("cd /etc/stool && sudo git pull 2>&1");
    }
}
