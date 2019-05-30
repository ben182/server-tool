<?php

namespace App\Console\Commands\DeamonCreate;

use App\Console\Command;

class DeamonCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deamon:create';

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
        $sName    = $this->ask('Name?');
        $sCommand = $this->ask('Command?');

        DeamonCreateTaskManager::work([
            'name'    => $sName,
            'command' => $sCommand,
        ]);
    }
}
