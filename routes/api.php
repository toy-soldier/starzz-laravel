<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::prefix('constellations')->group(__DIR__ . '/api/constellation.php');
Route::prefix('galaxies')->group(__DIR__ . '/api/galaxy.php');
Route::prefix('stars')->group(__DIR__ . '/api/star.php');
Route::prefix('users')->group(__DIR__ . '/api/user.php');
Route::prefix('login')->group(__DIR__ . '/api/auth.php');
