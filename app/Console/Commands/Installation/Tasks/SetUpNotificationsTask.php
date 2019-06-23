<?php

namespace App\Console\Commands\Installation\Tasks;

use App\Console\Task;

class SetUpNotificationsTask extends Task
{
    public $name = 'Set up Notifications';

    public function localRequirements()
    {
        return ! ! $this->options->slack_webhook;
    }

    public function handle()
    {
        $this->shell->replaceStringInFile('SLACK_WEBHOOK_URL=""', 'SLACK_WEBHOOK_URL="' . $this->options->slack_webhook . '"', '/etc/netdata/health_alarm_notify.conf');
        $this->shell->replaceStringInFile('DEFAULT_RECIPIENT_SLACK=""', 'DEFAULT_RECIPIENT_SLACK="' . $this->options->slack_recipient . '"', '/etc/netdata/health_alarm_notify.conf');

        $this->shell->service()->restart('netdata');
    }
}
