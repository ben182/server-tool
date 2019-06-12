<?php

namespace App\Helper;

class Check
{
    /**
     * Checks if a string is sha1.
     *
     * @param string $string
     *
     * @return bool
     */
    public function isSha1($string)
    {
        return (bool) preg_match('/^[0-9a-f]{40}$/i', $string);
    }

    /**
     * Gets all IPs from a string.
     *
     * @param string $string
     *
     * @return array
     */
    public function getIps($string)
    {
        if (preg_match_all('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $string, $ip_match)) {
            return $ip_match[0];
        }

        return [];
    }

    /**
     * Checks if a string is an IP
     *
     * @param string $string
     *
     * @return boolean
     */
    public function isIp($string) {
        return !empty($this->getIps($string));
    }

    /**
     * Checks if a given domain is a subdomain.
     *
     * @param string $sDomain
     *
     * @return bool
     */
    public function isSubdomain($domain)
    {
        return count(explode('.', $domain)) >= 3;
    }
}
