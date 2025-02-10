<?php

namespace Gnarhard\SongLink\Models;

use Illuminate\Database\Eloquent\Model;

class SongLink extends Model
{
    protected $guarded = [];

    protected $casts = [
        'links' => 'array',
    ];

    protected $hidden = ['raw_response'];

    public function getLink(string $platform): ?string
    {
        return $this->links[$platform]['url'] ?? null;
    }

    /**
     * Get an array of platform URLs.
     *
     * This accessor will return an array where the keys are the platform names
     * (e.g., "spotify", "itunes", "youtube", etc.) and the values are the corresponding
     * URLs for that platform.
     */
    public function getPlatformUrlsAttribute(): array
    {
        $platformUrls = [];

        // Ensure that links is an array
        $links = $this->links ?? [];

        foreach ($links as $platform => $details) {
            // Check if the details array has a 'url' key and add it to the result.
            if (isset($details['url'])) {
                $platformUrls[$platform] = $details['url'];
            }
        }

        unset($platformUrls['amazonStore']);
        unset($platformUrls['youtube']);

        // Correct the spelling of platform names
        foreach (array_keys($platformUrls) as $platform) {
            $details = $platformUrls[$platform];
            unset($platformUrls[$platform]);
            $platformUrls[$this->fixPlatformPunctuation($platform)] = $details;
        }

        return $platformUrls;
    }

    private function fixPlatformPunctuation(string $platform): string
    {
        $platformMap = [
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
        ];

        return $platformMap[$platform] ?? $platform;
    }

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
}
