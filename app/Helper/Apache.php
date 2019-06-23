<?php

namespace App\Helper;

use App\Helper\Shell\Shell;

class Apache
{
    protected $shell;
    protected $check;

    public function __construct(Shell $shell, Check $check)
    {
        $this->shell = $shell;
        $this->check = $check;
    }

    public function getAllDomainsEnabled()
    {
        $output = $this->shell->setQuitForNextCommand()->exec('sudo apache2ctl -t -D DUMP_VHOSTS')->getLastOutput();

        $ignore = $this->check->getIps($output);
        $ignore = array_merge($ignore, [
            'localhost',
        ]);

        if (preg_match_all('/(?<=namevhost ).\S*/', $output, $matches)) {
            return collect(array_diff($matches[0], $ignore))->unique()->values();
        }

        return collect();
    }

    public function getEnabledPhpVersion()
    {
        $phpConfs = glob("/etc/apache2/conf-enabled/php*-fpm.conf");

        if (empty($phpConfs)) {
            return false;
        }

        if (count($phpConfs) > 1) {
            throw new Exception('There are two or more PHP apache version configurations enabled');
        }

        return getStringBetween($phpConfs[0], '/php', '-fpm.conf');
    }

    public function getOwnPublicIp()
    {
        return once(function() {
            return trim(file_get_contents('https://ipinfo.io/ip'));
        });
    }
}
