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
        $this->shell->removeFolder("/var/www/{$this->oOptions->domain}");

        $this->addConclusion("Deleted /var/www/{$this->oOptions->domain} folder");
    }
}
