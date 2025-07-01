<?php

use Auth\Interface\Http\Controllers\API\V1\LoginController;
use Auth\Interface\Http\Controllers\API\V1\LogoutController;
use Auth\Interface\Http\Controllers\API\V1\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/login', LoginController::class)->name('login');
    Route::post('/register', RegisterController::class)->name('register');
    Route::get('/logout', LogoutController::class)->middleware('auth:sanctum')->name('logout');
});
