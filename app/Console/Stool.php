<?php

namespace App\Console;

use App\Helper\Github;

class Stool
{
    public static function versionString()
    {
        $commitHash = trim(exec('cd /etc/stool && git log --pretty="%h" -n1 HEAD'));

        $commitDate = new \DateTime(trim(exec('cd /etc/stool && git log -n1 --pretty=%ci HEAD')));
        $commitDate->setTimezone(new \DateTimeZone('UTC'));

        return sprintf('v%s-%s (%s)', self::version(), $commitHash, $commitDate->format('Y-m-d H:i:s'));
    }

    public static function version()
    {
        return trim(exec('cd /etc/stool && git describe --tags --abbrev=0'));
    }

    /**
     * Gets the latest version of stool from github and caches it.
     *
     * @return string
     */
    public static function versionOnRemote()
    {
        return once(function () {
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
