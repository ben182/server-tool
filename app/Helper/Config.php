<?php

namespace App\Helper;

class Config
{
    public function install()
    {
        return json_decode(file_get_contents(base_path('installation.json')), true);
    }

    public function isInstalled($sKey)
    {
        return $this->install()[$sKey] === 'true';
    }

    public function editInstall($sKey, bool $value)
    {
        $sOldValue = $this->install()[$sKey];

        $sFile = file_get_contents(base_path('installation.json'));

        $aKeys    = explode('.', $sKey);
        $sLastKey = $aKeys[count($aKeys) - 1];
        $sValue   = $value ? 'true' : 'false';

        return file_put_contents(base_path('installation.json'), str_replace(
            '"' . $sLastKey . '": "' . $sOldValue . '"',
            '"' . $sLastKey . '": "' . $sValue . '"',
            $sFile
        )) !== false;
    }

    public function getConfig($sKey = null) {
        $config = json_decode(file_get_contents(base_path('config.json')), true);

        if ($sKey) {
            return $config[$sKey];
        }

        return $config;
    }
}
