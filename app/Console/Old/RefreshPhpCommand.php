<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use App\Console\Commands\Tasks\RefreshPhpTaskManager;

class RefreshPhpCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'php:refresh';

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
        (new RefreshPhpTaskManager())->work();
    }
}
