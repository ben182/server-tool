<?php

namespace App\Helper;

class Github
{
    /**
     * Gets the latest version of a Github repo.
     *
     * @param string $sOwner
     * @param string $sRepo
     *
     * @return mixed The version. False in case of failure
     */
    public function getLatestVersion($sOwner, $sRepo)
    {
        $url   = "https://api.github.com/repos/$sOwner/$sRepo/releases/latest";
        $cInit = curl_init();
        curl_setopt($cInit, CURLOPT_URL, $url);
        curl_setopt($cInit, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cInit, CURLOPT_USERAGENT, 'Test');

        $output = curl_exec($cInit);

        $info    = curl_getinfo($cInit, CURLINFO_HTTP_CODE);
        $aReturn = json_decode($output, true);

        curl_close($cInit);

        if (! isset($aReturn['tag_name'])) {
            return false;
        }

        return $this->extractVersion($aReturn['tag_name']);
    }

    /**
     * Extracts a version from a string.
     *
     * @param string $sPayload
     *
     * @return mixed
     */
    protected function extractVersion($sPayload)
    {
        preg_match('/\d+(\.\d+)+/', $sPayload, $match);
        if (empty($match)) {
            return false;
        }

        return $match[0];
    }
}
