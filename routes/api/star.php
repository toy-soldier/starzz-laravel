<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StarController;

Route::get('/', [StarController::class, 'getStarList']);
Route::post('/', [StarController::class, 'registerStar']);

Route::where(['id' => '[0-9]+'])->group(function () {
    Route::get('/{id}', [StarController::class, 'getStar']);
    Route::put('/{id}', [StarController::class, 'updateStar']);
    Route::delete('/{id}', [StarController::class, 'deleteStar']);
});
