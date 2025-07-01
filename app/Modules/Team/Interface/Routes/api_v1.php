<?php

use Illuminate\Support\Facades\Route;
use Team\Interface\Http\Controllers\API\V1\TeamController;
use Team\Interface\Http\Controllers\API\V1\TeamMembersController;

Route::middleware('auth:sanctum')->group(function () {
    // Equipes
    Route::apiResource('teams', TeamController::class)->middleware('role:admin,manager');

    // Membros da equipe
    Route::name('members.')->controller(TeamMembersController::class)->group(function () {

        Route::middleware('role:admin,manager')->group(function () {
            Route::get('/teams/{id}/members',  'show')->name('show');
            Route::post('/teams/{id}/members',  'add')->name('add');
            Route::delete('/teams/{id}/members/{userId}', 'remove')->name('remove');
        });

        // Membro comum: só vê as equipes dele
        Route::middleware('role:member')->get('/my-teams',  'indexMyTeams')->name('my-teams');
    });
});
