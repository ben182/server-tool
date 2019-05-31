<?php

namespace App\Console\Commands\Tasks\Shell;

class MysqlUser
{
    protected $name;
    protected $password;
    protected $mysql;

    public function __construct($sName, $sPassword, $mysql)
    {
        $this->name     = $sName;
        $this->password = $sPassword;
        $this->mysql    = $mysql;
    }

    public function giveAccessToDatabase($sDatabase)
    {
        $this->mysql->execCommand("GRANT ALL PRIVILEGES ON $sDatabase.* To '{$this->name}'@'localhost'");
        $this->mysql->execCommand("FLUSH PRIVILEGES");

        return $this;
    }

    public function giveAccessToAllDatabases()
    {
        $this->mysql->execCommand("GRANT ALL PRIVILEGES ON * . * TO '{$this->name}'@'localhost'");
        $this->mysql->execCommand("FLUSH PRIVILEGES");

        return $this;
    }

    /**
     * Get the value of name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of password.
     */
    public function getPassword()
    {
        return $this->password;
    }
}
