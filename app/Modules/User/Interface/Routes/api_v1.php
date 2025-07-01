<?php

use Illuminate\Support\Facades\Route;
use User\Interface\Http\Controllers\API\V1\UserController;

Route::middleware('auth:sanctum')->name('users.')->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::get('/me', 'me')->name('me');
        Route::get('/users', 'index')->name('index');
        Route::get('/users/{user}', 'show')->name('show');
        Route::put('/users/{user}', 'update')->name('update');
    });
});

