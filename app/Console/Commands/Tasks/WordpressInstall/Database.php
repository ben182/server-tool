<?php

namespace App\Console\Commands\Tasks\WordpressInstall;

use App\Console\Commands\Tasks\Task;

class Database extends Task
{
    public $sName = 'Setting up Database';

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
        $sDatabaseName = $this->shell->mysql()->createDatabase($this->bindings->installationDir);
        $oUser = $this->shell->mysql()->createUser()->giveAccessToDatabase($sDatabaseName);

        $this->addConclusion('[DB]');
        $this->addConclusion('Database: ' . $sDatabaseName);
        $this->addConclusion('User: ' . $oUser->getName());
        $this->addConclusion('Password: ' . $oUser->getPassword());
    }
}
