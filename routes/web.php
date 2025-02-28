<?php

namespace Gnarhard\SongLink;

use Gnarhard\SongLink\Http\Controllers\SongLinkController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/listen', [SongLinkController::class, 'show'])->name('listen');
});
