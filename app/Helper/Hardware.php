<?php

namespace App\Helper;

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

    public function getSwapSizeRecommendation()
    {
        $ram = (int) round($this->getTotalRam());
        if ($ram < 2) {
            return $ram * 2;
        }

        if ($ram > 8) {
            return 8;
        }

        return $ram;
    }
}
