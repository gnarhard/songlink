<?php

namespace Gnarhard\SongLink;

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/listen', [PageController::class, 'showListenPage'])->name('listen');
});
