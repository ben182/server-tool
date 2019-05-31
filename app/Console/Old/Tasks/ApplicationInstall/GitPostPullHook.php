<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Task;

class GitPostPullHook extends Task
{
    public $name = 'Installing Git Post Pull Hook';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->options->gitPostPullHook;
    }

    public function handle()
    {
        copy(templates_path('git/post-merge'), "{$this->bindings->installationDir}/.git/hooks/post-merge");
        $this->shell->exec("chmod +x {$this->bindings->installationDir}/.git/hooks/post-merge");
    }
}
