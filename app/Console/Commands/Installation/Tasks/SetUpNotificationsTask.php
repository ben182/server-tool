<?php

namespace App\Console\Commands\Installation\Tasks;

use App\Console\Task;

class SetUpNotificationsTask extends Task
{
    public $name = 'Set up Notifications';

    public function handle()
    {
        $this->shell->replaceStringInFile('SLACK_WEBHOOK_URL=""', 'SLACK_WEBHOOK_URL="' . $this->bindings['netdata']['slack_webhook'] . '"', '/etc/netdata/health_alarm_notify.conf');

        $this->shell->service()->restart('netdata');
    }
}
