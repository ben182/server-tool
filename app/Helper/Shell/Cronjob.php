<?php

namespace App\Helper\Shell;

class Cronjob
{
    public function create($Interval, $sCommand)
    {
        return app('stool-shell')->exec('crontab -l | { cat; echo "' . $Interval . ' ' . $sCommand .  ' >> /dev/null 2>&1"; } | crontab -');
    }
}
