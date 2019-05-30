<?php

namespace App\Console\Commands\Tasks\PhpOpcache;

use App\Console\Commands\Tasks\Task;
use App\Console\Commands\Tasks\RefreshPhpTaskManager;

class PhpOpcacheTask extends Task
{
    public $name = 'Changing Opcache';

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

        $iInvertedMode = (int) ! $this->options->mode;
        $this->shell->replaceStringInFile("opcache.enable=$iInvertedMode", "opcache.enable=" . $this->options->mode, "/etc/php/$sVersion/fpm/php.ini");

        $this->addConclusion(($this->options->mode == 0 ? 'Disabled' : 'Enabled') . " Opcache");

        (new RefreshPhpTaskManager())->work();
    }
}
