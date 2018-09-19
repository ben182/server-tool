<?php

namespace App\Console\Commands\Tasks\PhpOpcache;

use App\Console\Commands\Tasks\Task;
use App\Console\Commands\Tasks\RefreshPhpTaskManager;

class PhpOpcacheTask extends Task
{
    public $sName = 'Changing Opcache';

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

        $iInvertedMode = (int) !$this->oOptions->mode;
        replace_string_in_file("/etc/php/$sVersion/fpm/php.ini", "[stool opcache]\nopcache.enable=$iInvertedMode", "[stool opcache]\nopcache.enable=" . $this->oOptions->mode);

        (new RefreshPhpTaskManager())->work();
    }
}
