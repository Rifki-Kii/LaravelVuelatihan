<?php

use App\Presentation\Http\Controllers\V1\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('tasks/{task}/comments', [CommentController::class, 'index']);
Route::apiResource('comments', CommentController::class)->except(['index', 'create', 'edit', 'update']);
