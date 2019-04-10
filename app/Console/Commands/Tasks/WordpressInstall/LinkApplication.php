<?php

namespace App\Console\Commands\Tasks\WordpressInstall;

use App\Console\Commands\Tasks\Task;

class LinkApplication extends Task
{
    public $sName = 'Linking Application';

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
        switch ($this->oOptions->rootOrSub) {
            case 'Root':

                if (file_exists($this->bindings->domain->getHtmlFolder())) {
                    $this->shell->exec("mv {$this->bindings->domain->getHtmlFolder()} {$this->bindings->domain->getBaseFolder()}/html_backup");
                    $this->addConclusion("There is already an html folder so I moved it to html_backup");
                }

                $this->shell->exec("sudo ln -s {$this->bindings->installationDir} {$this->bindings->domain->getHtmlFolder()}");

                break;
            case 'Sub':

                $this->bindings->domain->createHtmlFolder();

                $this->shell->exec("sudo ln -s {$this->bindings->installationDir} {$this->bindings->domain->getHtmlFolder()}/{$this->oOptions->subDir}");

                break;
            default:
                break;
        }

        $this->addConclusion("Application URL is {$this->bindings->domain->getFullUrl()}" . ($this->oOptions->subDir ? '/' . $this->oOptions->subDir : ''));

        $this->addConclusion("Site is now linked and live");
    }
}
