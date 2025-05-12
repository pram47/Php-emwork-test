<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\WorkLogController;

// Route::get('/work-logs', [WorkLogController::class, 'index']);
// Route::post('/work-logs', [WorkLogController::class, 'store']);
// Route::put('/work-logs/{workLog}', [WorkLogController::class, 'update']); // Use model binding name
// Route::delete('/work-logs/{workLog}', [WorkLogController::class, 'destroy']);
// Route::get('/work-logs/summary', [WorkLogController::class, 'monthlySummary']);

use App\Http\Controllers\WorkLogController;

Route::apiResource('work-logs', WorkLogController::class);
