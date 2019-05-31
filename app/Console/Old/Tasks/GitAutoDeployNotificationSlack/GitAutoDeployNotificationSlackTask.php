<?php

namespace App\Console\Commands\Tasks\GitAutoDeployNotificationSlack;

use App\Setting;
use App\Console\Task;
use App\Services\ApiRequestService;
use App\Console\Commands\GitAutoDeployNotificationSlack;

class GitAutoDeployNotificationSlackTask extends Task
{
    public $name = 'Setting up Slack';

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
        $oBody = app(ApiRequestService::class)->request('slack/isTokenSet');
        if ($oBody->ok === false) {
            return $this->shell->saveError('No write Permission! Please visit ' . GitAutoDeployNotificationSlack::generateOauthUrl() . ' and give permission to send Slack messages!');
        }

        Setting::updateOrCreate([
            'key'   => 'slack_channel',
        ], [
            'value' => $this->options->channel,
        ]);
    }
}
