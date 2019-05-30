<?php

namespace App\Console\Commands\Tasks\MysqlCreate;

use App\Console\Commands\Tasks\Task;

class CreateUserTask extends Task
{
    public $name = 'Creating MySQL User';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->options->newUserAndAccess;
    }

    public function handle()
    {
        $oUser = $this->shell->mysql()->createUser()->giveAccessToDatabase($this->bindings->databaseSlug);
        $this->addConclusion('Created new user');
        $this->addConclusion('Username: ' . $oUser->getName());
        $this->addConclusion('Password: ' . $oUser->getPassword());
    }
}
