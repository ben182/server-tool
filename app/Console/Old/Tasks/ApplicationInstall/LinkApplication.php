<?php

namespace App\Console\Commands\Tasks\ApplicationInstall;

use App\Console\Commands\Tasks\Task;

class LinkApplication extends Task
{
    public $name = 'Linking Application';

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
        switch ($this->options->rootOrSub) { // TODO clean up
            case 'Root':

                if (file_exists($this->bindings->domain->getHtmlFolder())) {
                    $this->shell->exec("mv {$this->bindings->domain->getHtmlFolder()} {$this->bindings->domain->getBaseFolder()}/html_backup");
                    $this->addConclusion("There is already an html folder so I moved it to html_backup");
                }

                if ($this->options->directoryOrSymlink == 'directory') {
                    $this->shell->exec("sudo ln -s {$this->bindings->installationDir} {$this->bindings->domain->getHtmlFolder()}");
                }

                if ($this->options->directoryOrSymlink == 'symlink') {
                    $this->shell->exec("sudo ln -s {$this->bindings->installationDir}/{$this->options->symlinkSourceDir} {$this->bindings->domain->getHtmlFolder()}");
                }
                break;
            case 'Sub':

                $this->bindings->domain->createHtmlFolder();

                if ($this->options->directoryOrSymlink == 'directory') {
                    $this->shell->exec("sudo ln -s {$this->bindings->installationDir} {$this->bindings->domain->getHtmlFolder()}/{$this->options->subDir}");
                }

                if ($this->options->directoryOrSymlink == 'symlink') {
                    $this->shell->exec("sudo ln -s {$this->bindings->installationDir}/{$this->options->symlinkSourceDir} {$this->bindings->domain->getHtmlFolder()}/{$this->options->subDir}");
                }
                break;
            default:
                break;
        }

        $this->addConclusion("Application URL is {$this->bindings->domain->getFullUrl()}" . ($this->options->subDir ? '/' . $this->options->subDir : ''));
    }
}
