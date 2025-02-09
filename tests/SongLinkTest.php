<?php

use Gnarhard\SongLink\Facades\SongLink as SongLinkFacade;
use Gnarhard\SongLink\Models\SongLink;
use Illuminate\Support\Facades\Artisan;

it('formats command arguments properly', function () {
    $songTitle  = 'Mist';
    $slug       = 'mist';
    $spotifyUrl = 'https://open.spotify.com/track/4hhbLHdsBw4y0AR9iBV0CN?si=7086c6871b9c49a1';

    Artisan::call('songlink:store', [
        'spotifyUrl' => $spotifyUrl,
        'title'      => $songTitle,
        'slug'       => $slug,
        'isSingle'   => true,
    ]);
});

it('can fetch song links', function () {
    $spotifyUrl = 'https://open.spotify.com/track/4hhbLHdsBw4y0AR9iBV0CN?si=7086c6871b9c49a1';

    $result = SongLinkFacade::fetchSongLinks($spotifyUrl);

    dd($result);

    expect('entityUniqueId')->toBeString();
    expect('linksByPlatform')->toBeArray();
    expect('entitiesByUniqueId')->toBeArray();
});

it('can store song links', function () {
    $songTitle  = 'Mist';
    $slug       = 'mist';
    $spotifyUrl = 'https://open.spotify.com/track/4hhbLHdsBw4y0AR9iBV0CN?si=7086c6871b9c49a1';

    expect(SongLink::count())->toBe(0);

    Artisan::call('songlink:store', [
        'spotifyUrl' => $spotifyUrl,
        'title'      => $songTitle,
        'slug'       => $slug,
        'isSingle'   => true,
    ]);

    expect(SongLink::count())->toBe(1);
});
