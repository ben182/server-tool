<?php

namespace App\Helper\Shell;

class Environment
{
    public function save($sKey, $sValue)
    {
        return app('stool-shell')->exec('sudo sh -c "echo "' . $sKey . '=\'' . $sValue . '\' >> /etc/environment"');
    }
}
