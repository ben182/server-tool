<?php

namespace App\Console\Commands\Tasks\GitAutoDeployNotificationSlack;

use App\Console\Commands\Tasks\Task;
use App\Services\ApiRequestService;
use App\Setting;
use App\Console\Commands\GitAutoDeployNotificationSlack;

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
        $oBody = (new ApiRequestService())->request('slack/isTokenSet');
        if ($oBody->ok === false) {
            return $this->shell->saveError('No write Permission! Please visit ' . GitAutoDeployNotificationSlack::generateOauthUrl() . ' and give permission to send Slack messages!');
        }

        Setting::updateOrCreate([
            'key'   => 'slack_channel',
        ], [
            'value' => $this->oOptions->channel,
        ]);
    }
}
