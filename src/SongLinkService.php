<?php

namespace Gnarhard\SongLink;

use Gnarhard\SongLink\Models\SongLink;
use Illuminate\Support\Facades\Http;

class SongLinkService
{
    /**
     * The API endpoint URL.
     */
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('songlink.api_url');
    }

    /**
     * Fetch song links from the Odesli API using a Spotify URL.
     */
    public function fetchSongLinks(string $spotifyUrl, bool $isSingle): ?array
    {
        $response = Http::get($this->apiUrl, [
            'url' => $spotifyUrl,
            'songIfSingle' => $isSingle,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    /**
     * Fetch and store song links in the database.
     */
    public function storeSongLinks(string $spotifyUrl, string $title, string $slug, bool $isSingle): ?SongLink
    {
        $data = $this->fetchSongLinks($spotifyUrl, $isSingle);

        if ($data) {
            // Extract the links data from the API response.
            // (The structure may change; adjust accordingly.)
            $links = $data['linksByPlatform'] ?? [];

            // Create or update a SongLink record.
            $songLink = SongLink::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'slug' => $slug,
                    'links' => $links,
                    'raw_response' => json_encode($data),
                ]
            );

            return $songLink;
        }

        return null;
    }
}
