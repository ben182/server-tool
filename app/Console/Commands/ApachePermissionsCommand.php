<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\ApachePermissionsTaskManager;
use App\Console\ModCommand;

class ApachePermissionsCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apache:permissions';

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

        (new ApachePermissionsTaskManager())->work();
    }
}
