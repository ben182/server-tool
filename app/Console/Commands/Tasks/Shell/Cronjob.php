<?php

namespace App\Console\Commands\Tasks\Shell;

class Cronjob
{
    protected $shell;
    public function __construct()
    {
        $this->shell = resolve('ShellTask');
    }

    public function create($Interval, $sCommand)
    {
        $this->shell->exec('crontab -l | { cat; echo "' . $Interval . ' ' . $sCommand .  ' >> /dev/null 2>&1"; } | crontab -');
    }
}
