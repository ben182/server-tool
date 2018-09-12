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
        (new ApiRequestService())->request('verifySlack', [
            'public_id' => $this->oOptions->public_id,
            'channel' => $this->oOptions->channel,
        ]); // TODO: validate response

        Setting::create([
            'key'   => 'deploy_slack_token',
            'value' => $this->oOptions->public_id,
        ]);
    }
}
