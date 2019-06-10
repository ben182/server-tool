<?php

namespace App\Rules;

use App\Helper\Domain;
use Illuminate\Contracts\Validation\Rule;
use App\Helper\Apache;

class DomainExists implements Rule
{
    protected $apache;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Apache $apache)
    {
        $this->apache = $apache;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->apache->getAllDomainsEnabled()->contains($value);
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
