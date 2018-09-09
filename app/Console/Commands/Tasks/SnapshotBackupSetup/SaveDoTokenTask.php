<?php

namespace App\Console\Commands\Tasks\SnapshotBackupSetup;

use App\Console\Commands\Tasks\Task;

class SaveDoTokenTask extends Task
{
    public $sName = 'Saving DigitalOcean Token';

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
        $this->shell->environment()->save('DOAT', encrypt($this->oOptions->doToken));
    }
}