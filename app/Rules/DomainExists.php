<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Helper\Domain;

class DomainExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (new Domain($value))->doesExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The domain is not bound to your system.';
    }
}
