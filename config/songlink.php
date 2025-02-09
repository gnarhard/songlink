<?php

// config/odesli.php

return [

    /*
    |--------------------------------------------------------------------------
    | Odesli API URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL for the Odesli (SongLink) API.
    |
    */
    'api_url' => env('SONGLINK_API_URL', 'https://api.song.link/v1-alpha.1/links'),

    /*
    |--------------------------------------------------------------------------
    | Default Platforms
    |--------------------------------------------------------------------------
    |
    | You can list the platforms you are interested in. In the API response
    | the links are grouped by platform.
    |
    */
    'default_platforms' => [
        'spotify',
        'appleMusic',
        'youtube',
        'deezer',
        // add more as needed...
    ],
];
