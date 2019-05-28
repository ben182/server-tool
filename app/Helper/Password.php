<?php

namespace App\Helper;

use App\Helper\Shell\Shell;
use Illuminate\Support\Str;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Hackzilla\PasswordGenerator\RandomGenerator\Php7RandomGenerator;

class Password
{
    /**
     * Generates a secure 15 - 20 character password with letters, numbers and symbols
     *
     * @return string
     */
    public function generate() {
        $generator = new ComputerPasswordGenerator();

        $generator
        ->setRandomGenerator(new Php7RandomGenerator())
          ->setUppercase()
          ->setLowercase()
          ->setNumbers()
          ->setSymbols(true)
          ->setLength(random_int(15, 20));

        return $generator->generatePassword();
    }
}
