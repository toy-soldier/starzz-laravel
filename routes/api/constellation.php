<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConstellationController;

Route::prefix('constellations')->group(function () {
    Route::get('/', [ConstellationController::class, 'getConstellationList']);
    Route::get('/{id}', [ConstellationController::class, 'getConstellation']);
    Route::post('/', [ConstellationController::class, 'registerConstellation']);
    Route::put('/{id}', [ConstellationController::class, 'updateConstellation']);
    Route::delete('/{id}', [ConstellationController::class, 'deleteConstellation']);
});
 