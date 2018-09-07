<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\GitAutoDeployTaskManager;
use App\Console\Commands\Tasks\Task;

class Gad extends Task
{
    public $sName = 'Enabling Git Auto Deploy';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->oOptions->gad;
    }

    public function handle()
    {
        (new GitAutoDeployTaskManager([
            'dir'    => $this->bindings->installationDir,
        ]))->work();
    }
}
