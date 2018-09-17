<?php

namespace App\Console\Commands\Tasks\GitAutoDeployNotificationSlack;

use App\Console\Commands\Tasks\Task;
use App\Services\ApiRequestService;
use App\Setting;

class GitAutoDeployNotificationSlackTask extends Task
{
    public $sName = 'Setting up Slack';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return true;
    }

    public function handle()
    {
        Setting::create([
            'key'   => 'slack_channel',
            'value' => $this->oOptions->channel,
        ]);
    }
}
