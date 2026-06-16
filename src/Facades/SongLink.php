<?php

namespace Gnarhard\SongLink\Facades;

use Gnarhard\SongLink\SongLinkService;
use Illuminate\Support\Facades\Facade;

/**
 * @see SongLinkService
 */
class SongLink extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SongLinkService::class;
    }
}
