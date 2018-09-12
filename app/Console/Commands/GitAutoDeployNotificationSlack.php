<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\ModCommand;
use App\Console\Commands\Tasks\GitAutoDeployNotificationSlackTaskManager;

class GitAutoDeployNotificationSlack extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gad:notification-slack';

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

        $this->line('Visit ' . config('services.stool.base') . '/deploy/login/slack and come back with a token');
        $sToken = $this->ask('Token?');
        $sChannel = $this->ask('Channel?');

        (new GitAutoDeployNotificationSlackTaskManager([
            'public_id' => $sToken,
            'channel' => $sChannel,
        ]))->work();
    }
}
