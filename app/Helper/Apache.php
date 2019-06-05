<?php

namespace App\Helper;

use App\Helper\Shell\Shell;

class Apache
{
    public function getAllDomainsEnabled() {
        $output = app('stool-shell')->setQuitForNextCommand()->exec('sudo apache2ctl -t -D DUMP_VHOSTS')->getLastOutput();

        if (preg_match_all('/(?<=namevhost ).\S*/', $output, $matches)) {
            return $matches[0];
        }

        return [];
    }
}
