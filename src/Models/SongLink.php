<?php

namespace Gnarhard\SongLink\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property array $links
 * @property array $common_platform_urls set by SongLinkController, not stored
 * @property array $uncommon_platform_urls set by SongLinkController, not stored
 */
class SongLink extends Model
{
    protected $guarded = [];

    protected $casts = [
        'links' => 'array',
    ];

    protected $hidden = ['raw_response'];
}
