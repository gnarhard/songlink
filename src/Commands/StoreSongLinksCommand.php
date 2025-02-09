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
    protected $signature = 'songlink:store {spotifyUrl : The Spotify URL of the song} {isSingle=true : Whether the song is a single or not} {title : The title of the song} {slug : A slug of the song to be used when generating URLs}';

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
        $spotifyUrl = $this->argument('spotifyUrl');
        $isSingle = $this->argument('isSingle');
        $title = $this->argument('title');
        $slug = $this->argument('slug');

        $this->info("Fetching song links for: {$title}");

        $songLink = SongLink::storeSongLinks($spotifyUrl, $title, $slug);

        if ($songLink) {
            $this->info("Song links stored successfully!");
            $this->line(print_r($songLink->toArray(), true));
        } else {
            $this->error("Failed to fetch or store song links.");
        }
    }
}