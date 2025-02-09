<?php

namespace Gnarhard\SongLink\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gnarhard\SongLink\SongLinkService
 */
class SongLink extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Gnarhard\SongLink\SongLinkService::class;
    }
}
