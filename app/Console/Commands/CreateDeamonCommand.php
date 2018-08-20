<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use App\Console\Commands\Tasks\CreateDeamonTaskManager;

class CreateDeamonCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deamon:create {--name=} {--command=}';

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
        $sName = $this->stringOption('name', 'Name?');
        $sCommand = $this->stringOption('command', 'Command?');

        (new CreateDeamonTaskManager([
            'name' => $sName,
            'command' => $sCommand,
        ]))->work();
    }
}
