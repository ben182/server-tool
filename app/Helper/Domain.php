<?php

namespace App\Helper;

class Domain
{
    protected $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function __toString()
    {
        return $this->domain;
    }

    public function getApacheSite()
    {
        return "/etc/apache2/sites-enabled/{$this->domain}.conf";
    }

    public function getApacheSslSite()
    {
        return "/etc/apache2/sites-enabled/{$this->domain}-le-ssl.conf";
    }

    public function doesExist()
    {
        return file_exists($this->getApacheSite());
    }

    public function doesNotExist()
    {
        return ! file_exists($this->getApacheSite());
    }

    public function isSSL()
    {
        return file_exists($this->getApacheSslSite());
    }

    public function isNotSSL()
    {
        return ! file_exists($this->getApacheSslSite());
    }

    public function createHtmlFolder()
    {
        if (! file_exists($this->getHtmlFolder())) {
            mkdir($this->getHtmlFolder(), 0755, true);
        }
    }

    public function getHtmlFolder()
    {
        return $this->getBaseFolder() . "/html";
    }

    public function getBaseFolder()
    {
        return "/home/stool/{$this->domain}";
    }

    public function getProtocol()
    {
        return $this->isSSL() ? 'https://' : 'http://';
    }

    public function getFullUrl($sSubDir = null)
    {
        return $this->getProtocol() . $this->domain . ($sSubDir ? '/' . $sSubDir : '');
    }

    public function getARecord()
    {
        $dns = dns_get_record($this->domain);
        if (! $dns) {
            return false;
        }

        $a = collect($dns)->firstWhere('type', 'A');

        if (! $a) {
            return false;
        }

        return $a['ip'];
    }

    public function isBoundToThisServer()
    {
        return app('stool-apache')->getOwnPublicIp() === $this->getARecord();
    }
}
