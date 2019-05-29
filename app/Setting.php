<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function createKey($key, $value)
    {
        return static::create([
            'key'   => $key,
            'value' => $value,
        ]);
    }

    public static function getKey($key) {
        return static::where('key', $key)->value('value');
    }
}
