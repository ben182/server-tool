<?php

namespace App\Console\Commands\Tasks\RefreshPhp;

use App\Console\Commands\Tasks\Task;

class RefreshPhpTask extends Task
{
    public $sName = 'Refreshing PHP Version';

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
        $aPhp = glob("/etc/apache2/conf-enabled/php*-fpm.conf");
        if (empty($aPhp)) {
            $this->shell->addError('No Apache PHP configuration enabled');
        }

        $sVersion = getStringBetween($aPhp[0], '/php', '-fpm.conf');

        $this->shell->exec("sudo /etc/init.d/php$sVersion-fpm restart");

        $this->addConclusion("Restarted PHP $sVersion");
    }
}
