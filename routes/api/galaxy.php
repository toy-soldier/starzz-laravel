<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalaxyController;

Route::prefix('galaxies')->group(function () {
    Route::get('/', [GalaxyController::class, 'getGalaxyList']);
    Route::get('/{id}', [GalaxyController::class, 'getGalaxy']);
    Route::post('/', [GalaxyController::class, 'registerGalaxy']);
    Route::put('/{id}', [GalaxyController::class, 'updateGalaxy']);
    Route::delete('/{id}', [GalaxyController::class, 'deleteGalaxy']);
});
 