<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/', [AuthController::class, 'login']);
