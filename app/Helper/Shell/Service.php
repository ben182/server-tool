<?php

namespace App\Helper\Shell;

class Service
{
    public function restart($name)
    {
        return app('stool-shell')->exec("sudo service $name restart");
    }
}
