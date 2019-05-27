<?php

namespace App\Helper\Shell;

class Environment
{
    protected $shell;

    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    public function save($sKey, $sValue)
    {
        // $this->shell->exec('echo "' . $sKey . '=\"' . $sValue . '\"" >> /etc/environment');
        $this->shell->exec('sudo sh -c "echo "' . $sKey . '=\'' . $sValue . '\' >> /etc/environment"');
    }
}
