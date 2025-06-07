<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [AuthController::class, 'user']);

    Route::group(['prefix' => 'team'], static function () {
        // apiResource ->only gamoviyeno, route names mianichebs, testebshi gamomadgeba
        Route::get('/', [TeamsController::class, 'index']);
        Route::get('/{team}', [TeamsController::class, 'show']);
        Route::put('/{team}', [TeamsController::class, 'update']);
    });
});
