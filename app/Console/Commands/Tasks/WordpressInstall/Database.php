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

        $wpconfig = file_get_contents("{$this->bindings->installationDir}/wp-config.php");
        $wpconfig = str_replace('database_name_here', $sDatabaseName, $wpconfig);
        $wpconfig = str_replace('username_here', $oUser->getName(), $wpconfig);
        $wpconfig = str_replace('password_here', $oUser->getPassword(), $wpconfig);

        file_put_contents("{$this->bindings->installationDir}/wp-config.php", $wpconfig);
    }
}
