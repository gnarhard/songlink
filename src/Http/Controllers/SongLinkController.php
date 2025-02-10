<?php

namespace Gnarhard\SongLink\Http\Controllers;

use Gnarhard\SongLink\Models\SongLink;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class SongLinkController extends Controller
{
    public function show(Request $request): View
    {
        $song = null;
        $songSlug = $request->query('s') ?? $request->query('song') ?? $request->query('t');

        if ($songSlug != null) {
            $song = SongLink::where('slug', $songSlug)->first();
        }

        if ($song == null) {
            $song = SongLink::latest()->first();
        }

        return view('listen', ['song' => $song]);
    }
}
