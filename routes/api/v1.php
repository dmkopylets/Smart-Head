<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

{
    Route::resource('genres', App\Http\Controllers\Api\V1\GenreController::class)->except(['create']);
    Route::post('genres/create', [App\Http\Controllers\Api\V1\GenreController::class, 'create']);
    Route::resource('movies', App\Http\Controllers\Api\V1\MovieController::class)->except(['create']);
    Route::post('movies/create', [App\Http\Controllers\Api\V1\MovieController::class, 'create']);
}
