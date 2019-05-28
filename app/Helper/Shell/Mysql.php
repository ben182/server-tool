<?php

namespace App\Helper\Shell;

use Illuminate\Support\Str;
use App\Helper\Password;
use App\Helper\Increment;

class Mysql
{
    protected $shell;
    protected $password;

    protected $increment;

    public function __construct(Shell $shell, Password $password, Increment $increment)
    {
        $this->shell = $shell;
        $this->password = $password;
        $this->increment = $increment;
    }

    public function createDatabase($sDatabaseName, $bCheckIfExist = true)
    {
        $sSluggedDatabaseName = Str::slug($sDatabaseName, null);

        if ($this->doesDatabaseExist($sSluggedDatabaseName) && $bCheckIfExist) {
            do {
                $sSluggedDatabaseName = $this->increment->increment($sSluggedDatabaseName);

                $bExist = $this->doesDatabaseExist($sSluggedDatabaseName);
            } while ($bExist);
        }

        $this->execCommand("CREATE DATABASE $sSluggedDatabaseName;");

        return $sSluggedDatabaseName;
    }

    public function createUser($sName = null, $sPassword = null)
    {
        if (! $sName) {
            do {
                $sName = Str::random(random_int(10, 15));

                $bExist = $this->doesUserExist($sName);
            } while ($bExist);
        }

        if (! $sPassword) {
            $sPassword = $this->password->generate();
        }

        $this->execCommand("CREATE USER '$sName'@'localhost' IDENTIFIED BY '$sPassword';");

        return new MysqlUser($sName, $sPassword, $this);
    }

    public function execCommand($sCommand)
    {
        $this->shell->setQuitForNextCommand();
        return $this->shell->exec('mysql ' . getMysqlCredentials() . " -e \"$sCommand\"");
    }

    public function doesDatabaseExist($sDatabase)
    {
        return Str::contains($this->execCommand("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  '$sDatabase';")->getLastOutput(), $sDatabase);
    }

    public function doesUserExist($user)
    {
        return Str::contains($this->execCommand("SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = '$user')")->getLastOutput(), "1\n");
    }
}
