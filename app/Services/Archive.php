<?php

namespace App\Services;

class Archive
{
    public static function make($sName, $sSource) {
        resolve('ShellTask')->exec("tar -zcvf $sName.tar.gz $sSource");
    }

    public static function extract($sName) {
        resolve('ShellTask')->exec("tar -zxvf $sName.tar.gz");
    }
}
