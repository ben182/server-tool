<?php

namespace App\Helper\Shell;

class Cronjob
{
    protected $shell;

    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    public function create($Interval, $sCommand)
    {
        return $this->shell->exec('crontab -l | { cat; echo "' . $Interval . ' ' . $sCommand .  ' >> /dev/null 2>&1"; } | crontab -');
    }
}
