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
        return app('stool-apache')->getEnabledPhpVersion() !== false;
    }

    public function handle()
    {
        $version = app('stool-apache')->getEnabledPhpVersion();

        $this->shell->exec("sudo /etc/init.d/php$version-fpm restart");

        $this->addConclusion("Restarted PHP $version");
    }
}
