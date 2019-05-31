<?php

namespace App\Console\Commands\Tasks\SnapshotBackupSetup;

use App\Console\Task;

class SaveDoTokenTask extends Task
{
    public $name = 'Saving DigitalOcean Token';

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
        $this->shell->environment()->save('DOAT', encrypt($this->options->doToken));
    }
}
