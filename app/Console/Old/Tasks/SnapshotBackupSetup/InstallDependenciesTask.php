<?php

namespace App\Console\Commands\Tasks\SnapshotBackupSetup;

use App\Console\Task;

class InstallDependenciesTask extends Task
{
    public $name = 'Installing Dependencies';

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
        $this->shell->exec('sudo gem install do_snapshot');
    }
}
