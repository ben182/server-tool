<?php

namespace App\Console\Commands\Tasks\DeleteVhost;

use App\Console\Task;

class DeleteDomainFolderTask extends Task
{
    public $name = 'Deleting Domain Folder';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->options->deleteDomainFolder;
    }

    public function handle()
    {
        $this->shell->removeFolder("/home/stool/{$this->options->domain}");

        $this->addConclusion("Deleted /home/stool/{$this->options->domain} folder");
    }
}
