<?php

namespace App\Helper;

use App\Helper\Shell\Shell;

class Hardware
{
    /**
     * Gets the total available RAM in GB.
     *
     * @return float
     */
    public function getTotalRam()
    {
        $fh  = fopen('/proc/meminfo', 'r');
        $mem = 0;
        while ($line = fgets($fh)) {
            $pieces = [];
            if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                $mem = $pieces[1];
                break;
            }
        }
        fclose($fh);

        return $mem / 1024 / 1024;
    }

    /**
     * Checks if a specific port is already blocked.
     *
     * @param int $iPort
     *
     * @return bool
     */
    public function checkIfPortIsUsed($port)
    {
        $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 5);
        if (! $fp) {
            return false;
        }

        fclose($fp);

        return true;
    }
}
