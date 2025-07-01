<?php

use Illuminate\Support\Facades\Route;
use Task\Interface\Http\Controllers\API\V1\TaskController;
use Task\Interface\Http\Controllers\API\V1\TaskDelegationController;
use Task\Interface\Http\Controllers\API\V1\TeamTasksController;

Route::middleware('auth:sanctum')->group(function () {
    // Equipes
    Route::apiResource('tasks', TaskController::class);

    Route::middleware('role:admin,manager')->group(function () {
        Route::post('/tasks/delegate', TaskDelegationController::class)->name('tasks.delegate');
    });

    Route::get('/teams/{team}/tasks', TeamTasksController::class)->name('team.tasks');
});
