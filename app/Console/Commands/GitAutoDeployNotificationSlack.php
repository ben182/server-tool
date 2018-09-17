<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\ModCommand;
use App\Console\Commands\Tasks\GitAutoDeployNotificationSlackTaskManager;
use App\Setting;

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

        $sServerId = Setting::where('key', 'server_id')->value('value');

        $this->info('Visit ' . config('services.stool.base') . '/deploy/login/slack/' . $sServerId . ' and give permission to send Slack messages!');
        $sChannel = $this->ask('Channel?');

        (new GitAutoDeployNotificationSlackTaskManager([
            'channel' => $sChannel,
        ]))->work();
    }
}
