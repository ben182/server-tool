<?php

namespace App\Helper;

class Increment
{
    /**
     * Increments a given name. If name has no number as a last character 2 will be appended. If it has a number it will be incremented.
     *
     * @param string $sName
     *
     * @return string
     */
    public function increment($string)
    {
        if (! preg_match("/(.*?)(\d+)$/", $string, $matches)) {
            return $string . '2';
        }

        return $matches[1].($matches[2] + 1);
    }
}
