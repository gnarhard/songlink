<?php

namespace Gnarhard\SongLink\Commands;

use Gnarhard\SongLink\Facades\SongLink;
use Illuminate\Console\Command;

class StoreSongLinksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'songlink:store {--spotifyUrl= : The Spotify URL of the song} {--title= : The title of the song} {--slug= : A slug of the song to be used when generating URLs} {--isSingle : Whether the song is a single (flag; omit for album)} {--albumArtworkPath= : The path to the album artwork} {--youtubeVideoId= : The YouTube video ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store song links using the Odesli API for a given Spotify URL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $spotifyUrl = $this->option('spotifyUrl');
        $title = $this->option('title');
        $slug = $this->option('slug');
        $isSingle = $this->option('isSingle');
        $albumArtworkPath = $this->option('albumArtworkPath');
        $youtubeVideoId = $this->option('youtubeVideoId');

        if (! $spotifyUrl || ! $title || ! $slug) {
            $this->error('--spotifyUrl, --title, and --slug are required.');

            return 1;
        }
        $this->info("Fetching song links for: {$title}");

        $songLink = SongLink::storeSongLinks($spotifyUrl, $title, $slug, $isSingle, $albumArtworkPath, $youtubeVideoId);

        if ($songLink) {
            $this->info('Song links stored successfully!');
            $this->line(print_r($songLink->toArray(), true));
        } else {
            $this->error('Failed to fetch or store song links.');
        }
    }
}
