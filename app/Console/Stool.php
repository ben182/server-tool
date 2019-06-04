<?php

namespace App\Console;

use App\Helper\Github;

class Stool
{
    protected static $versionOnRemote;

    public static function version()
    {
        return '2.0.0';
    }

    public static function versionOnRemote()
    {
        return static::$versionOnRemote ?: static::$versionOnRemote = app(Github::class)->getLatestVersion('ben182', 'server-tool');
    }

    /**
     * Checks if an update for stool is available.
     *
     * @return bool
     */
    public static function updateAvailable()
    {
        return version_compare(static::versionOnRemote(), static::version(), '>');
    }
}
