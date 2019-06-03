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

        $phpIniFile = "/etc/php/$sVersion/fpm/php.ini";

        $iInvertedMode = (int) ! $this->options->mode;
        $this->shell->replaceStringInFile("opcache.enable=$iInvertedMode", "opcache.enable=" . $this->options->mode, $phpIniFile);

        if ($this->options->mode) {

            if (preg_match_all('/opcache.validate_timestamps=([\d]+)/', file_get_contents($phpIniFile), $matches)) {
                $end = $matches[1];
                $validateTimestampValue = (int) array_pop($end);
            }
            $this->shell->replaceStringInFile("opcache.validate_timestamps=$validateTimestampValue", "opcache.validate_timestamps=" . (int) $this->options->validateTimestamps, $phpIniFile);
        }

        $this->addConclusion(($this->options->mode == 0 ? 'Disabled' : 'Enabled') . " Opcache");
    }
}
