<?php

namespace Gnarhard\SongLink\Facades;

use Gnarhard\SongLink\SongLinkService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array|null fetchSongLinks(string $spotifyUrl, bool $isSingle = false)
 * @method static \Gnarhard\SongLink\Models\SongLink|null storeSongLinks(string $spotifyUrl, string $title, string $slug, bool $isSingle = true, ?string $album_artwork_path = null, ?string $youtube_video_id = null)
 * @method static string|null getLink(array $links, string $platform)
 * @method static array getPlatformUrls(array $links, bool $common = true)
 *
 * @see SongLinkService
 */
class SongLink extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SongLinkService::class;
    }
}
