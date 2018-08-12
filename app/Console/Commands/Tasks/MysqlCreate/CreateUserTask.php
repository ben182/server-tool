<?php

namespace App\Console\Commands\Tasks\MysqlCreate;

use App\Console\Commands\Tasks\Task;

class CreateUserTask extends Task
{
    public $sName = 'Creating MySQL User';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return $this->oOptions->newUserAndAccess;
    }

    public function handle()
    {
        $aUser = createMysqlUserAndGiveAccessToDatabase($this->bindings->databaseSlug);
        $this->addConclusion('Created new user');
        $this->addConclusion('Username: ' . $aUser['user']);
        $this->addConclusion('Password: ' . $aUser['password']);
    }
}
