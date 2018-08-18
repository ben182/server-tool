<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MysqlDatabaseExist implements Rule
{
    public $shell;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->shell = resolve('ShellTask');
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
        return $this->shell->mysql()->doesDatabaseExist($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The database :attribute does not exist.';
    }
}
