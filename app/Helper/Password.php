<?php

namespace App\Helper;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Hackzilla\PasswordGenerator\RandomGenerator\Php7RandomGenerator;

class Password
{
    /**
     * Generates a secure 18 - 25 character password with letters and numbers.
     *
     * @return string
     */
    public function generate()
    {
        $generator = new ComputerPasswordGenerator();

        $generator
          ->setRandomGenerator(new Php7RandomGenerator())
          ->setUppercase()
          ->setLowercase()
          ->setNumbers()
          ->setLength(random_int(18, 25));

        return $generator->generatePassword();
    }
}
