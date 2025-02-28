<?php

namespace Gnarhard\SongLink;

use Gnarhard\SongLink\Models\SongLink;
use Illuminate\Support\Facades\Http;

class SongLinkService
{
    /**
     * The display/popularity order for platforms.
     */
    protected array $popularityOrder = [
        'Spotify',
        'Apple Music',
        'YouTube Music',
        'Amazon',
        'Deezer',
        'SoundCloud',
        'Tidal',
        'Pandora',
        'iTunes',
        'Bandcamp',
        'Boomplay',
        'Anghami',
        'Napster',
    ];

    /**
     * Get the display/popularity order for platforms.
     */
    public function getPopularityOrder(): array
    {
        return $this->popularityOrder;
    }

    /**
     * The Odesli (SongLink) API endpoint URL.
     */
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('songlink.api_url');
    }

    /**
     * Fetch song links from the Odesli API using a Spotify URL.
     *
     * @param  bool  $isSingle  Whether this track is a single (affects the Odesli query param)
     */
    public function fetchSongLinks(string $spotifyUrl, bool $isSingle = false): ?array
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
    public function storeSongLinks(string $spotifyUrl, string $title, string $slug, bool $isSingle, ?string $album_artwork_path = null, ?string $youtube_video_id = null): ?SongLink
    {
        $data = $this->fetchSongLinks($spotifyUrl, $isSingle);

        if ($data === null) {
            return null;
        }

        // Extract the links data from the API response
        $links = $data['linksByPlatform'] ?? [];

        // Create or update a SongLink record
        return SongLink::updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'slug' => $slug,
                'links' => $links,
                'youtube_video_id' => $youtube_video_id,
                'album_artwork_path' => $album_artwork_path,
                'raw_response' => json_encode($data),
            ]
        );
    }

    /**
     * Get the URL for a specific platform from an array of links.
     *
     * @param  array  $links  e.g. $songLink->links
     */
    public function getLink(array $links, string $platform): ?string
    {
        return $links[$platform]['url'] ?? null;
    }

    /**
     * Create a sorted, human-friendly list of platform names => URLs.
     *
     * @param  array  $links  Raw 'linksByPlatform' array
     */
    public function getPlatformUrls(array $links): array
    {
        // Remove unwanted platforms up front
        unset($links['amazonStore'], $links['youtube']);

        // Build new array with corrected platform names
        $platformUrls = [];
        foreach ($links as $platformKey => $details) {
            if (empty($details['url'])) {
                continue;
            }

            // Convert internal key to a nicer display name
            $displayName = $this->fixPlatformPunctuation($platformKey);
            $platformUrls[$displayName] = $details['url'];
        }

        // Sort by popularity
        return $this->sortPlatformUrls($platformUrls);
    }

    /**
     * Convert internal platform keys to nicer display names.
     */
    protected function fixPlatformPunctuation(string $platform): string
    {
        $map = [
            'itunes' => 'iTunes',
            'spotify' => 'Spotify',
            'youtube' => 'YouTube',
            'tidal' => 'Tidal',
            'deezer' => 'Deezer',
            'amazonMusic' => 'Amazon',
            'youtubeMusic' => 'YouTube Music',
            'appleMusic' => 'Apple Music',
            'anghami' => 'Anghami',
            'bandcamp' => 'Bandcamp',
            'soundcloud' => 'SoundCloud',
            'pandora' => 'Pandora',
            'boomplay' => 'Boomplay',
            'napster' => 'Napster',
        ];

        return $map[$platform] ?? $platform;
    }

    /**
     * Sort the given [PlatformName => URL] array by $popularityOrder.
     */
    protected function sortPlatformUrls(array $platformUrls): array
    {
        $order = $this->popularityOrder;

        // We'll sort by key, because the key is the platform name (e.g. 'Spotify').
        // Using uksort so keys remain intact.
        uksort($platformUrls, function ($a, $b) use ($order) {
            $posA = array_search($a, $order, true);
            $posB = array_search($b, $order, true);

            // Handle platforms not found in $popularityOrder by pushing them to the end
            $posA = ($posA === false) ? PHP_INT_MAX : $posA;
            $posB = ($posB === false) ? PHP_INT_MAX : $posB;

            return $posA <=> $posB;
        });

        return $platformUrls;
    }

    /**
     * Get a Font Awesome icon class for a given platform name.
     *
     * @param  string  $platform  e.g. 'Spotify', 'Apple Music', ...
     */
    public static function getPlatformIcon(string $platform): string
    {
        $iconMap = [
            'iTunes' => 'fab fa-itunes-note',
            'Spotify' => 'fab fa-spotify',
            'YouTube' => 'fab fa-youtube',
            'Deezer' => 'fab fa-deezer',
            'Amazon' => 'fab fa-amazon',
            'YouTube Music' => 'fab fa-youtube',
            'Apple Music' => 'fab fa-apple',
            'Bandcamp' => 'fab fa-bandcamp',
            'SoundCloud' => 'fab fa-soundcloud',
        ];

        return $iconMap[$platform] ?? 'fas fa-link';
    }

    public static function getPlatformConversionValue(string $platform): float
    {
        $map = [
            'Spotify' => .003,
            'Apple Music' => .006,
            'YouTube Music' => .0003,
            'Amazon' => .004,
            'Deezer' => .0064,
            'Pandora' => .0013,
            'Tidal' => .0125,
            'iTunes' => .99,
            'Bandcamp' => .99,
            'Boomplay' => .003,
            'Anghami' => .0035,
            'Napster' => .019,
            'SoundCloud' => .003275,
        ];

        return $map[$platform] ?? .0035;
    }

    public static function getPlatformClickId(string $platform): string
    {
        $map = [
            'Spotify' => 'spotify_stream',
            'Apple Music' => 'apple_music_stream',
            'YouTube Music' => 'youtube_music_stream',
            'Amazon' => 'amazon_stream',
            'Deezer' => 'deezer_stream',
            'Pandora' => 'pandora_stream',
            'Tidal' => 'tidal_stream',
            'iTunes' => 'itunes_download',
            'Bandcamp' => 'bandcamp_download',
            'Boomplay' => 'boomplay_stream',
            'Anghami' => 'anghami_stream',
            'Napster' => 'napster_stream',
            'SoundCloud' => 'soundcloud_stream',
            'Share' => 'share_listen_page',
        ];

        return $map[$platform] ?? .0035;
    }
}
