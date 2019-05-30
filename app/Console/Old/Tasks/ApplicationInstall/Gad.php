<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\Task;
use App\Console\Commands\Tasks\GitAutoDeployTaskManager;

class Gad extends Task
{
    public $name = 'Enabling Git Auto Deploy';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->options->gad;
    }

    public function handle()
    {
        (new GitAutoDeployTaskManager([
            'dir'    => $this->bindings->installationDir,
            'branch' => $this->options->branch,
        ]))->work();
    }
}
