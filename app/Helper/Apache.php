<?php

namespace App\Helper;

use App\Helper\Shell\Shell;

class Apache
{
    protected $shell;

    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    public function getAllDomainsEnabled() {
        $output = $this->shell->setQuitForNextCommand()->exec('sudo apache2ctl -t -D DUMP_VHOSTS')->getLastOutput();

        if (preg_match_all('/(?<=namevhost ).\S*/', $output, $matches)) {
            return $matches[0];
        }

        return [];
    }
}
