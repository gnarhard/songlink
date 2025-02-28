<?php

namespace Gnarhard\SongLink\Http\Controllers;

use Gnarhard\SongLink\Facades\SongLink as FacadesSongLink;
use Gnarhard\SongLink\Models\SongLink;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SongLinkController
{
    /**
     * Show the listen page.
     */
    public function show(Request $request): View
    {
        $songSlug = $request->query('s')
            ?? $request->query('song')
            ?? $request->query('t')
            ?? SongLink::select('slug', 'created_at')->latest()->value('slug');

        // $song = cache()->remember("song_{$songSlug}", now()->addYear(), function () use ($songSlug) {
            $song = SongLink::where('slug', $songSlug)->first();
            // if ($song == null) {
            //     return null;
            // }
            $song->common_platform_urls = FacadesSongLink::getPlatformUrls($song->links, true);
            $song->uncommon_platform_urls = FacadesSongLink::getPlatformUrls($song->links, false);

            // return $song;
        // });

        if ($song == null) {
            return view('errors.404');
        }

        return view('pages.listen', ['song' => $song]);
    }
}
