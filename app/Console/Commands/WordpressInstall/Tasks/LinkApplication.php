<?php

namespace App\Console\Commands\WordpressInstall\Tasks;

use App\Console\Task;

class LinkApplication extends Task
{
    public $name = 'Linking Application';

    public function handle()
    {
        switch ($this->options->rootOrSub) {
            case 'Root':

                if (file_exists($this->bindings->domain->getHtmlFolder())) {
                    $this->shell->exec("mv {$this->bindings->domain->getHtmlFolder()} {$this->bindings->domain->getBaseFolder()}/html_backup");
                    $this->addConclusion("There is already an html folder so I moved it to html_backup");
                }

                $this->shell->exec("ln -s -f {$this->bindings->installationDir} {$this->bindings->domain->getHtmlFolder()}");

                break;
            case 'Sub':

                $this->bindings->domain->createHtmlFolder();

                $this->shell->exec("ln -s -f {$this->bindings->installationDir} {$this->bindings->domain->getHtmlFolder()}/{$this->options->subDir}");

                break;
        }

        $this->addConclusion("Application URL is {$this->bindings->domain->getFullUrl()}" . ($this->options->subDir ? '/' . $this->options->subDir : ''));

        $this->addConclusion("Site is now linked and live");
    }
}
