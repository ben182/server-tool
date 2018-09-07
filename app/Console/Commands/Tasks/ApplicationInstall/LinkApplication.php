<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

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
        switch ($this->oOptions->rootOrSub) { // TODO clean up
            case 'Root':

                if (file_exists($this->bindings->domain->getHtmlFolder())) {
                    $this->shell->exec("mv {$this->bindings->domain->getHtmlFolder()} {$this->bindings->domain->getBaseFolder()}/html_backup");
                    $this->addConclusion("There is already an html folder so I moved it to html_backup");
                }

                if ($this->oOptions->directoryOrSymlink == 'directory') {
                    $this->shell->exec("ln -s {$this->bindings->installationDir} {$this->bindings->domain->getHtmlFolder()}");
                }

                if ($this->oOptions->directoryOrSymlink == 'symlink') {
                    $this->shell->exec("ln -s {$this->bindings->installationDir}/{$this->oOptions->symlinkSourceDir} {$this->bindings->domain->getHtmlFolder()}");
                }
                break;
            case 'Sub':

                $this->bindings->domain->createHtmlFolder();

                if ($this->oOptions->directoryOrSymlink == 'directory') {
                    $this->shell->exec("ln -s {$this->bindings->installationDir} {$this->bindings->domain->getHtmlFolder()}/{$this->oOptions->subDir}");
                }

                if ($this->oOptions->directoryOrSymlink == 'symlink') {
                    $this->shell->exec("ln -s {$this->bindings->installationDir}/{$this->oOptions->symlinkSourceDir} {$this->bindings->domain->getHtmlFolder()}/{$this->oOptions->subDir}");
                }
                break;
            default:
                break;
        }

        $this->addConclusion("Application URL is {$this->bindings->domain->getFullUrl()}" . ($this->oOptions->subDir ? '/' . $this->oOptions->subDir : ''));
    }
}
