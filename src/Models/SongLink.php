<?php

namespace Gnarhard\SongLink\Models;

use Illuminate\Database\Eloquent\Model;

class SongLink extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'is_single',
        'links',
        'raw_response',
    ];

    protected $casts = [
        'links' => 'array',
    ];

    protected $hidden = ['raw_response'];
}
