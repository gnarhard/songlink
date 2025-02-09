<?php

namespace Gnarhard\SongLink\Models;

use Illuminate\Database\Eloquent\Model;

class SongLink extends Model {
    protected $guarded = [];

    protected $casts = [
        'links'        => 'array',
    ];
}