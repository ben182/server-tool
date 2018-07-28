<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        Log::info(shell_exec('php artisan down'));
        Log::info(shell_exec('php artisan view:clear'));

        Log::info(shell_exec('php artisan view:cache'));
        Log::info(shell_exec('php artisan config:cache'));
        Log::info(shell_exec('php artisan route:cache'));

        if (file_exists(getcwd() . '/yarn.lock')) {
            Log::info(shell_exec('yarn prod'));
        } else {
            Log::info(shell_exec('npm run prod'));
        }

        Log::info(shell_exec('php artisan up'));
        //shell_exec('nohup bash ' . scripts_path() . 'laravel.production.sh > /dev/null 2>&1 &');
    }
}
