<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Helper\Shell\Shell;

class MysqlDatabaseExistNot implements Rule
{
    protected $shell;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->shell = app(Shell::class);
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
        return ! $this->shell->mysql()->doesDatabaseExist($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The database :attribute does already exist.';
    }
}
