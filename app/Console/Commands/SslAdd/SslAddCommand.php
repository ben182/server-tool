<?php

namespace App\Console\Commands\SslAdd;

use App\Console\Command;
use App\Console\Commands\AddVhost\AddVhostTaskManager;

class SslAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds an SSL certificate';

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
        SslAddTaskManager::work();
    }
}
