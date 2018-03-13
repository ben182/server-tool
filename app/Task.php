<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['command', 'parameter', 'frequency', 'frequency_parameter'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'parameter' => 'array',
        'frequency_parameter' => 'array',
    ];

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        if ($this->getCastType($key) == 'array' && is_null($value)) {
            return [];
        }

        return parent::castAttribute($key, $value);
    }
}
