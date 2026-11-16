<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalaxyController;

Route::get('/', [GalaxyController::class, 'getGalaxyList']);
Route::post('/', [GalaxyController::class, 'registerGalaxy']);

Route::where(['id' => '[0-9]+'])->group(function () {
    Route::get('/{id}', [GalaxyController::class, 'getGalaxy']);
    Route::put('/{id}', [GalaxyController::class, 'updateGalaxy']);
    Route::delete('/{id}', [GalaxyController::class, 'deleteGalaxy']);
});
