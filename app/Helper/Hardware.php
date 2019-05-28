<?php

namespace App\Helper;

class Hardware
{

    /**
     * Gets the total available RAM in GB
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
}
