<?php

namespace App\Console\Commands\OpcacheChange;

use App\Console\Command;

class OpcacheChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'php:opcache {mode}';

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
        OpcacheChangeTaskManager::work([
            'mode' => $this->argument('mode'),
        ]);
    }
}
