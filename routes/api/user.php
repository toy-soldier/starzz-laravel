<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Public
Route::where(['id' => '[0-9]+'])->group(function () {
    Route::patch('/{id}/change-password', [UserController::class, 'changePassword']);
});

// Requires a valid token
Route::middleware('jwt.auth')->group(function () {
    Route::get('/', [UserController::class, 'getUserList']);

    Route::where(['id' => '[0-9]+'])->group(function () {
        Route::get('/{id}', [UserController::class, 'getUser']);
        Route::put('/{id}', [UserController::class, 'updateUser']);
    });

    // Requires the ADMIN role
    Route::middleware('role:ADMIN')->post('/', [UserController::class, 'registerUser']);
});
