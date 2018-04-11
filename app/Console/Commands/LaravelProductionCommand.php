<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use Illuminate\Console\Command;

class LaravelProductionCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel:production';

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
        shell_exec('nohup bash ' . scripts_path() . 'laravel.production.sh > /dev/null 2>&1 &');
    }
}
