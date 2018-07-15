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
        shell_exec('php artisan down');
        shell_exec('php artisan view:clear');

        shell_exec('php artisan view:cache');
        shell_exec('php artisan config:cache');
        shell_exec('php artisan route:cache');

        if (file_exists(getcwd() . '/yarn.lock')) {
            shell_exec('yarn prod');
        }else{
            shell_exec('npm run prod');
        }

        shell_exec('php artisan up');
        //shell_exec('nohup bash ' . scripts_path() . 'laravel.production.sh > /dev/null 2>&1 &');
    }
}
