<?php

use Audit\Interface\Http\Controllers\API\V1\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin,manager'])->name('audit.')->group(function () {
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
});
