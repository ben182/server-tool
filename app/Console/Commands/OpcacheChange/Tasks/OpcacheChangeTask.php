<?php

namespace App\Console\Commands\OpcacheChange\Tasks;

use App\Console\Task;

class OpcacheChangeTask extends Task
{
    public $name = 'Changing Opcache';

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

        $sVersion = getStringBetween($phpConfs[0], '/php', '-fpm.conf');

        $iInvertedMode = (int) ! $this->options->mode;
        $this->shell->replaceStringInFile("opcache.enable=$iInvertedMode", "opcache.enable=" . $this->options->mode, "/etc/php/$sVersion/fpm/php.ini");

        $this->addConclusion(($this->options->mode == 0 ? 'Disabled' : 'Enabled') . " Opcache");
    }
}
