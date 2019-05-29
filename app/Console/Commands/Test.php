<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Console\CommandHolder;
use App\Console\Commands\AddVhost\TestTaskManager;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

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
        // dd(CommandHolder::$command);
        // CommandHolder::getCommand()->line('test');
        TestTaskManager::work([
            'test' => 'test',
        ]);
    }
}
