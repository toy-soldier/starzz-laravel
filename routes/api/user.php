<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class, 'getUserList']);
Route::post('/', [UserController::class, 'registerUser']);

Route::where(['id' => '[0-9]+'])->group(function () {
    Route::get('/{id}', [UserController::class, 'getUser']);
    Route::put('/{id}', [UserController::class, 'updateUser']);
    Route::patch('/{id}/change-password', [UserController::class, 'changePassword']);
});
