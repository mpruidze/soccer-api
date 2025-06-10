<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PlayersController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\TransfersController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::delete('/token', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    Route::get('/user', [AuthController::class, 'getUser'])->middleware('auth:sanctum')->name('auth.user');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('/teams', TeamsController::class)->only(['show', 'update']);
    Route::apiResource('/players', PlayersController::class)->only(['show', 'update']);
    Route::apiResource('/transfers', TransfersController::class)->except('destroy');
    Route::patch('/transfers/{transfer}/status', [TransfersController::class, 'confirm'])->name('transfers.confirm');
});
