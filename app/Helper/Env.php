<?php

namespace App\Helper;

class Env {
    public function setKey($sKey, $sValue, $sPath = null) {

        $sPath = $sPath ?: base_path('.env');

        if (! file_exists($sPath)) {
            return false;
        }

        $sFile = file_get_contents($sPath);

        preg_match("/(?<=$sKey=).*/", $sFile, $match);

        if (! isset($match[0])) {
            return false;
        }

        file_put_contents($sPath, str_replace(
            "$sKey=" . $match[0],
            "$sKey=" . $sValue,
            $sFile
        ));

        return true;
    }
}
