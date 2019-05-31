<?php

namespace App\Console\Commands\Partials;

use Exception;
use App\Console\Task;

class RestartPhpTask extends Task
{
    public $name = 'Restarting PHP';

    public $systemRequirementsErrorMessage = 'No Apache PHP configuration enabled';

    public function systemRequirements()
    {
        return ! empty(glob("/etc/apache2/conf-enabled/php*-fpm.conf"));
    }

    public function handle()
    {
        $phpConfs = glob("/etc/apache2/conf-enabled/php*-fpm.conf");

        if (count($phpConfs) > 1) {
            throw new Exception('There are two or more PHP apache version configurations enabled');
        }

        $version = getStringBetween($phpConfs[0], '/php', '-fpm.conf');

        $this->shell->exec("sudo /etc/init.d/php$version-fpm restart");

        $this->addConclusion("Restarted PHP $version");
    }
}
