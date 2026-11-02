<?php

use App\Presentation\Http\Controllers\V1\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tasks', TaskController::class);
Route::patch('tasks/{task}/status', [TaskController::class, 'changeStatus']);
