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

        $this->shell->replaceStringInFile('database_name_here', $sDatabaseName, "{$this->bindings->installationDir}/wp-config.php");
        $this->shell->replaceStringInFile('username_here', $oUser->getName(), "{$this->bindings->installationDir}/wp-config.php");
        $this->shell->replaceStringInFile('password_here', $oUser->getPassword(), "{$this->bindings->installationDir}/wp-config.php");
    }
}
