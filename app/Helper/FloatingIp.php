<?php

namespace App\Helper;

use App\Helper\Shell\Shell;

class FloatingIp
{
    protected $shell;
    protected $check;

    public function __construct(Shell $shell, Check $check)
    {
        $this->shell = $shell;
        $this->check = $check;
    }

    public function getAllIps()
    {
        $floatingIps = collect(glob('/etc/network/interfaces.d/*.cfg'));

        return $floatingIps
        ->map(function ($file) {
            return str_replace('.cfg', '', basename($file));
        })
        ->filter(function ($file) {
            return $this->check->isSha1($file);
        })
        ->map(function ($file) {
            $output = $this->shell->getFile('/etc/network/interfaces.d/' . $file . '.cfg');

            if ($ips = $this->check->getIps($output)) {
                return $ips[0];
            }

            return null;
        })
        ->filter()
        ->values();
    }
}
