<?php

namespace App\Console\Commands\Tasks\Shell;

class Environment
{
    protected $shell;
    public function __construct()
    {
        $this->shell = resolve('ShellTask');
    }

    public function save($sKey, $sValue)
    {
        $this->shell->exec('echo "' . $sKey . '=\"' . $sValue . '\"" >> /etc/environment');
    }
}
