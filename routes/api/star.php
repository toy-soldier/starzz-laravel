<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StarController;

Route::prefix('stars')->group(function () {
    Route::get('/', [StarController::class, 'getStarList']);
    Route::get('/{id}', [StarController::class, 'getStar']);
    Route::post('/', [StarController::class, 'registerStar']);
    Route::put('/{id}', [StarController::class, 'updateStar']);
    Route::delete('/{id}', [StarController::class, 'deleteStar']);
});
 