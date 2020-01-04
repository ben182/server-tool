<?php

namespace App\Console\Commands\WordpressInstall\Tasks;

use App\Console\Task;

class PageBuilder extends Task
{
    public $name = 'Installing Page Builder';

    public function localRequirements()
    {
        return $this->options->pageBuilder !== 'None';
    }

    public function handle()
    {
        switch ($this->options->pageBuilder) {
            case 'Elementor':
                $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install elementor --activate");
                break;
        }
    }
}
