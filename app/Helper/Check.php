<?php

namespace App\Helper;

use App\Helper\Shell\Shell;
use Illuminate\Support\Str;

class Check
{
    /**
     * Checks if a string is sha1
     *
     * @param string $string
     *
     * @return boolean
     */
    public function isSha1($string) {
        return (bool) preg_match('/^[0-9a-f]{40}$/i', $string);
    }

    public function getIps($string) {
        return preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $string, $ip_match);
    }

    /**
     * Checks if a given domain is a subdomain
     *
     * @param string $sDomain
     *
     * @return boolean
     */
    public function isSubdomain($domain)
    {
        return count(explode('.', $domain)) >= 3;
    }
}
