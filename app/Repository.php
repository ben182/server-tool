<?php

namespace App;

use Balping\HashSlug\HasHashSlug;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasHashSlug;

    protected $fillable = ['dir', 'branch', 'reset', 'secret'];

    protected $casts = [
        'reset' => 'boolean',
    ];

    public function _setSecret()
    {
        $this->attributes['secret'] = random_string_random_length();
    }

    public function getFullDir()
    {
        return '/var/www/' . $this->dir;
    }
}
