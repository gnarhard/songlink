<?php

use Gnarhard\SongLink\Facades\SongLink as SongLinkFacade;
use Gnarhard\SongLink\Models\SongLink;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

it('formats command arguments properly', function () {
    $songTitle = 'Mist';
    $slug = 'mist';
    $spotifyUrl = 'https://open.spotify.com/track/4hhbLHdsBw4y0AR9iBV0CN?si=7086c6871b9c49a1';

    SongLinkFacade::shouldReceive('storeSongLinks')
        ->once()
        ->with($spotifyUrl, $songTitle, $slug, true, null, null)
        ->andReturn(new SongLink([
            'title' => $songTitle,
            'slug' => $slug,
            'links' => [],
        ]));

    Artisan::call('songlink:store', [
        '--spotifyUrl' => $spotifyUrl,
        '--title' => $songTitle,
        '--slug' => $slug,
        '--isSingle' => true,
    ]);
});

it('can fetch song links', function () {
    $spotifyUrl = 'https://open.spotify.com/track/4hhbLHdsBw4y0AR9iBV0CN?si=7086c6871b9c49a1';

    Http::fake([
        '*' => Http::response([
            'entityUniqueId' => 'SPOTIFY_SONG::4hhbLHdsBw4y0AR9iBV0CN',
            'linksByPlatform' => ['spotify' => ['url' => $spotifyUrl]],
            'entitiesByUniqueId' => ['SPOTIFY_SONG::4hhbLHdsBw4y0AR9iBV0CN' => ['title' => 'Mist']],
        ]),
    ]);

    $result = SongLinkFacade::fetchSongLinks($spotifyUrl, true);

    expect($result['entityUniqueId'])->toBeString();
    expect($result['linksByPlatform'])->toBeArray();
    expect($result['entitiesByUniqueId'])->toBeArray();

    Http::assertSent(fn (Request $request) => $request['url'] === $spotifyUrl
        && str_starts_with($request->url(), config('songlink.api_url')));
});

it('returns null when the song.link API rejects the request', function () {
    Http::fake([
        '*' => Http::response(['statusCode' => 401, 'code' => 'PUBLIC_API_ACCESS_DEPRECATED'], 401),
    ]);

    expect(SongLinkFacade::fetchSongLinks('https://open.spotify.com/track/abc'))->toBeNull();
});

it('can create platform links', function () {
    $links = [
        'spotify' => [
            'url' => 'https://open.spotify.com/track/4hhbLHdsBw4y0AR9iBV0CN?si=7086c6871b9c49a1',
        ],
        'appleMusic' => [
            'url' => 'https://music.apple.com/us/album/mist/1577320002?i=1577320003',
        ],
        'anghami' => [
            'url' => 'https://play.anghami.com/song/106000',
        ],
        'itunes' => [
            'url' => 'https://music.apple.com/us/album/mist/1577320002?i=1577320003',
        ],
        'amazonMusic' => [
            'url' => 'https://music.amazon.com/albums/B09BZQZQZ3?marketplaceId=ATVPDKIKX0DER&musicTerritory=US',
        ],
        'deezer' => [
            'url' => 'https://deezer.com/track/2480073322',
        ],
        'tidal' => [
            'url' => 'https://tidal.com/browse/track/199073366',
        ],
        'youtubeMusic' => [
            'url' => 'https://music.youtube.com/watch?v=4hhbLHdsBw4',
        ],
        'youtube' => [
            'url' => 'https://www.youtube.com/watch?v=4hhbLHdsBw4',
        ],
        'soundcloud' => [
            'url' => 'https://soundcloud.com/artist/mist',
        ],
        'bandcamp' => [
            'url' => 'https://mist.bandcamp.com/track/mist',
        ],
        'napster' => [
            'url' => 'https://napster.com/track/tra.2480073322',
        ],
        'boomplay' => [
            'url' => 'https://www.boomplay.com/songs/2480073322',
        ],
        'pandora' => [
            'url' => 'https://pandora.com/track/2480073322',
        ],
    ];

    $songLink = SongLink::make([
        'title' => 'Test',
        'slug' => 'test',
        'links' => $links,
    ]);

    $platformUrls = SongLinkFacade::getPlatformUrls($links, true);
    $platformUrlsRaw = SongLinkFacade::getPlatformUrls($links, false);
    $combinedPlatformUrls = array_merge($platformUrls, $platformUrlsRaw);

    expect($combinedPlatformUrls)->not->toBeEmpty();

    // Check if the array keys have uppercase letters
    $hasUppercaseKey = false;
    foreach (array_keys($combinedPlatformUrls) as $key) {
        if (preg_match('/[A-Z]/', $key)) {
            $hasUppercaseKey = true;
            break;
        }
    }
    expect($hasUppercaseKey)->toBeTrue();

    // Check if the array values have URLs
    $hasUrlValue = false;
    foreach ($combinedPlatformUrls as $value) {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $hasUrlValue = true;
            break;
        }
    }
    expect($hasUrlValue)->toBeTrue();

    // Check if the array is sorted by popularity
    $popularity = SongLinkFacade::getPopularityOrder();
    $sortedKeys = array_keys($combinedPlatformUrls);
    // add any keys that are in the popularity list but not in the sortedKeys to the end of sortedKeys
    $sortedKeys = array_merge($sortedKeys, array_diff($popularity, $sortedKeys));
    expect($sortedKeys)->toBe($popularity);
});

// it('can store song links', function () {
//     $songTitle = 'Mist';
//     $slug = 'mist';
//     $spotifyUrl = 'https://open.spotify.com/track/4hhbLHdsBw4y0AR9iBV0CN?si=7086c6871b9c49a1';

//     expect(SongLink::count())->toBe(0);

//     Artisan::call('songlink:store', [
//         'spotifyUrl' => $spotifyUrl,
//         'title' => $songTitle,
//         'slug' => $slug,
//         'isSingle' => true,
//     ]);

//     expect(SongLink::count())->toBe(1);
// });

it('renders without a background when the song has no artwork', function () {
    $html = view('songlink::songlinks', [
        'song' => songForView(['title' => 'No Art', 'slug' => 'no-art']),
        'artist' => 'Someone',
    ])->render();

    expect($html)->toContain('No Art')->not->toContain('background: url(');
});

it('uses the given background image over the song artwork', function () {
    $html = view('songlink::songlinks', [
        'song' => songForView(['title' => 'Art', 'slug' => 'art', 'album_artwork_path' => 'images/art.webp']),
        'backgroundImage' => 'images/override.webp',
    ])->render();

    expect($html)->toContain('images/override.webp')->not->toContain('images/art.webp');
});

/**
 * A song prepared the way SongLinkController hands it to the view.
 */
function songForView(array $attributes): SongLink
{
    // The view links to the host app's mailing list page.
    Route::get('/mailing-list', fn () => '')->name('mailing-list');
    app('router')->getRoutes()->refreshNameLookups();

    $song = SongLink::make([...$attributes, 'links' => ['spotify' => ['url' => 'https://open.spotify.com/track/1']]]);
    $song->common_platform_urls = SongLinkFacade::getPlatformUrls($song->links, true);
    $song->uncommon_platform_urls = SongLinkFacade::getPlatformUrls($song->links, false);

    return $song;
}
