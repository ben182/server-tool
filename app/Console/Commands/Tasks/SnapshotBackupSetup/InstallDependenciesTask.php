<?php

namespace App\Console\Commands\Tasks\SnapshotBackupSetup;

use App\Console\Commands\Tasks\Task;

class InstallDependenciesTask extends Task
{
    public $sName = 'Installing Dependencies';

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
        $this->shell->exec('gem install do_snapshot');
    }
}
