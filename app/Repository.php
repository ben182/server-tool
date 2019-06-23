<?php

namespace App;

use Balping\HashSlug\HasHashSlug;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    use HasHashSlug;

    protected $fillable = [
        'dir',
        'branch',
        'secret',
    ];
}
