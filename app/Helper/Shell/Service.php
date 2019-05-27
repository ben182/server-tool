<?php

namespace App\Helper\Shell;

class Service
{
    protected $shell;

    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    public function restart($name)
    {
        $this->shell->exec("sudo service $name restart");
    }
}
