<?php

namespace App\Helper;

use App\Helper\Shell\Shell;
use Illuminate\Support\Str;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Hackzilla\PasswordGenerator\RandomGenerator\Php7RandomGenerator;

class Increment
{
    public function increment($string) {
        if (!preg_match("/(.*?)(\d+)$/", $string, $matches)) {
            return $string . '2';
        }

        return $matches[1].($matches[2]+1);
    }
}
