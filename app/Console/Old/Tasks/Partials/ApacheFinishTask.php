<?php

namespace App\Console\Commands\Tasks\Partials;

use App\Console\Commands\Tasks\Task;

class ApacheFinishTask extends Task
{
    public $name = 'Clean up & Finishing';

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
        fixApachePermissions();
        restartApache();

        $this->addConclusion('Reseted Apache Permissions and restarted Apache Server');
    }
}
