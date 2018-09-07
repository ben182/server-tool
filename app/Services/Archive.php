<?php

namespace App\Services;

class Archive
{
    protected $shell;

    public function __construct() {
        $this->shell = resolve('ShellTask');
    }

    public static function make($sName, $sSource) {
        $this->shell->exec("tar -zcvf $sName.tar.gz $sSource");
    }

    public static function extract($sName) {
        $this->shell->exec("tar -zxvf $sName.tar.gz");
    }
}
