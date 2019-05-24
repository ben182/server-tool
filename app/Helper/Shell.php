<?php

namespace App\Helper;

class Shell
{
    private $bDev = false;

    public function __construct()
    {
        $this->bDev = config('app.debug');
    }

    public function exec($sCommand)
    {
        $sReturn = shell_exec($sCommand . ' 2>&1');

        if ($this->bDev) {
            echo $sReturn;
        }

        return $sReturn;
    }

    public function execScript($sName)
    {
        $this->exec('bash ' . scripts_path() . $sName . '.sh');
    }

    public function execScriptAsStool($sName)
    {
        $this->exec('sudo -H -u stool bash ' . scripts_path() . $sName . '.sh');
    }
}
