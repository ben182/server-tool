<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DomainController extends Controller
{
    protected $sName;

    public function __construct($sName)
    {
        $this->sName = $sName;
    }

    public function doesExist() {
        return file_exists("/etc/apache2/sites-enabled/$this->sName.conf");
    }

    public function doesNotExist() {
        return !file_exists("/etc/apache2/sites-enabled/$this->sName.conf");
    }

    public function isSSL() {
        return file_exists("/etc/apache2/sites-enabled/$this->sName-le-ssl.conf");
    }

    public function isNotSSL() {
        return !file_exists("/etc/apache2/sites-enabled/$this->sName-le-ssl.conf");
    }

    public function createHtmlFolder() {
        if (!file_exists("/var/www/$this->sName/html")) {
            mkdir("/var/www/$this->sName/html", 755, TRUE);
        }
    }

    public function getProtocol() {
        return $this->isSSL() ? 'https://' : 'http://';
    }

    public function getFullUrl() {
        return $this->getProtocol() . $this->sName;
    }
}
