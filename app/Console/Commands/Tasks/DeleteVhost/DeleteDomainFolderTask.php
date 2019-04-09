<?php

namespace App\Console\Commands\Tasks\DeleteVhost;

use App\Console\Commands\Tasks\Task;

class DeleteDomainFolderTask extends Task
{
    public $sName = 'Deleting Domain Folder';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->oOptions->deleteDomainFolder;
    }

    public function handle()
    {
        $this->shell->removeFolder("/home/stool/{$this->oOptions->domain}");

        $this->addConclusion("Deleted /home/stool/{$this->oOptions->domain} folder");
    }
}
