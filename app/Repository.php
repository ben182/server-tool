<?php

namespace App;

use Balping\HashSlug\HasHashSlug;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasHashSlug;

    protected $fillable = ['dir', 'secret'];

    public function _setSecret()
    {
        $this->attributes['secret'] = random_string_random_length();
    }

    public function getFullDirAttribute()
    {
        return '/var/www/' . $this->dir;
    }
}
