<?php

namespace App\Console\Commands\Tasks\Shell;

class Mysql {
    protected $shell;
    public function __construct() {
        $this->shell = resolve('ShellTask');
    }

    public function createDatabase($sDatabaseName, $bCheckIfExist = true) {

        $sSluggedDatabaseName = str_slug($sDatabaseName, null);

        if ($this->doesDatabaseExist($sSluggedDatabaseName) && $bCheckIfExist) {
            do {
                $sSluggedDatabaseName = $this->incrementName($sSluggedDatabaseName);
    
                $bExist = $this->doesDatabaseExist($sSluggedDatabaseName);
            } while ($bExist);
        }

        $this->execCommand("CREATE DATABASE $sSluggedDatabaseName;");

        return $sSluggedDatabaseName;
    }
    
    public function createUser($sName = null, $sPassword = null) {

        if (! $sName) {
            $sName = str_random(10); // TODO: is already taken?
        }

        if (! $sPassword) {
            $sPassword = random_string_random_length(); // TODO: is already taken?
        }

        $this->execCommand("CREATE USER '$sName'@'localhost' IDENTIFIED BY '$sPassword';");

        return new MysqlUser($sName, $sPassword, $this);
    }

    public function execCommand($sCommand) {
        return $this->shell->exec('mysql ' . getMysqlCredentials() . " -e \"$sCommand\"");
    }

    /**
     * Increments a given name. If name has no number as a last character 2 will be appended. If it has a number (not 0) it will be incremented. Works only for the last character not for numbers >= 10. TODO: fix for larger numbers
     *
     * @param string $sName
     *
     * @return string
     */
    public function incrementName($sName) {
        $iLastChar = (int) substr($sName, -1);
        if ($iLastChar === 0) {
            return $sName . "2";
        }

        $sWithoutLastChar = substr($sName, 0, -1);
        return $sWithoutLastChar . (++$iLastChar);
    }

    public function doesDatabaseExist($sDatabase) {
        return str_contains($this->execCommand("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  '$sDatabase';")->getLastOutput(), $sDatabase);
    }
}