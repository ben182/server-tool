<?php

namespace App\Helper;

use App\Helper\Shell\Shell;
use Illuminate\Support\Str;

class Hardware
{
    protected $shell;

    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

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

    /**
     * Checks if a specific port is already blocked
     *
     * @param int $iPort
     * @return boolean
     */
    public function checkIfPortIsUsed($port) {
        $fp = @fsockopen('127.0.0.1', $port, $errno, $errstr, 5);
        if (!$fp) {
            return false;
        } else {
            fclose($fp);
            return true;
        }
    }
}
