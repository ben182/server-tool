<?php

namespace App\Console;

use App\Helper\Github;

class Stool
{
    public static function version()
    {
        return '2.0.3';
    }

    /**
     * Gets the latest version of stool from github and caches it.
     *
     * @return string
     */
    public static function versionOnRemote()
    {
        return once(function() {
            return app('stool-github')->getLatestVersion('ben182', 'server-tool');
        });
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
