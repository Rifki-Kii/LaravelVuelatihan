<?php

use App\Presentation\Http\Controllers\V1\TagController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tags', TagController::class)->only(['index', 'store']);
