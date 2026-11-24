<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConstellationController;

// Public
Route::get('/', [ConstellationController::class, 'getConstellationList']);

Route::where(['id' => '[0-9]+'])->group(function () {
    Route::get('/{id}', [ConstellationController::class, 'getConstellation']);
});

// Requires a valid token
Route::middleware('jwt.auth')->group(function () {
    Route::post('/', [ConstellationController::class, 'registerConstellation']);

    Route::where(['id' => '[0-9]+'])->group(function () {
        Route::put('/{id}', [ConstellationController::class, 'updateConstellation']);
        Route::delete('/{id}', [ConstellationController::class, 'deleteConstellation']);
    });
});
