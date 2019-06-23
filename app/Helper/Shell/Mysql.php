<?php

namespace App\Helper\Shell;

use App\Helper\Config;
use App\Helper\Password;
use App\Helper\Increment;
use Illuminate\Support\Str;

class Mysql
{
    protected $shell;
    protected $password;
    protected $increment;
    protected $config;

    public function __construct(Shell $shell, Password $password, Increment $increment, Config $config)
    {
        $this->shell     = $shell;
        $this->password  = $password;
        $this->increment = $increment;
        $this->config    = $config;
    }

    public function createDatabase($sDatabaseName, $bCheckIfExist = true)
    {
        $sSluggedDatabaseName = Str::slug($sDatabaseName, null);

        if ($bCheckIfExist) {
            while ($this->doesDatabaseExist($sSluggedDatabaseName)) {
                $sSluggedDatabaseName = $this->increment->increment($sSluggedDatabaseName);
            }
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

        return $this->shell->exec('mysql ' . $this->credentials() . " -e \"$sCommand\"");
    }

    public function doesDatabaseExist($sDatabase)
    {
        return Str::contains($this->execCommand("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  '$sDatabase';")->getLastOutput(), $sDatabase);
    }

    public function doesUserExist($user)
    {
        return Str::contains($this->execCommand("SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = '$user')")->getLastOutput(), "1\n");
    }

    protected function credentials()
    {
        $aMysql = $this->config->getConfig('mysql');

        $sMysqlUser     = $aMysql['username'];
        $sMysqlPassword = $aMysql['password'];

        return "-u $sMysqlUser -p\"$sMysqlPassword\"";
    }
}
