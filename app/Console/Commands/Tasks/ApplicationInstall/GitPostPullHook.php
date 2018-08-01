<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\Task;

class GitPostPullHook extends Task
{
    public $sName = 'Installing Git Post Pull Hook';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->oOptions->gitPostPullHook;
    }

    public function handle()
    {
        copy(templates_path('git/post-merge'), "{$this->bindings->installationDir}/.git/hooks/post-merge");
        $this->shell->exec("chmod +x {$this->bindings->installationDir}/.git/hooks/post-merge");
    }
}
