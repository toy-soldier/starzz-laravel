<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'getUserList']);
    Route::get('/{id}', [UserController::class, 'getUser']);
    Route::post('/', [UserController::class, 'registerUser']);
    Route::put('/{id}', [UserController::class, 'updateUser']);
});
 