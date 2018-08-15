<?php

namespace App\Console\Commands\Tasks\SnapshotBackupSetup;

use App\Console\Commands\Tasks\Task;

class InstallCronjobTask extends Task
{
    public $sName = 'Installing Cronjob';

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
        $this->shell->cronjob()->create('0 0 * * *', 'stool snapshot:backup ' . $this->oOptions->keep);
    }
}
