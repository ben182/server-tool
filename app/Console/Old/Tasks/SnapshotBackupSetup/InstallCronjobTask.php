<?php

namespace App\Console\Commands\Tasks\SnapshotBackupSetup;

use App\Console\Task;

class InstallCronjobTask extends Task
{
    public $name = 'Installing Cronjob';

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
        $this->shell->cronjob()->create('0 0 * * *', 'stool snapshot:backup ' . $this->options->keep);
    }
}
