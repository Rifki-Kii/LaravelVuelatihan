<?php

use App\Presentation\Http\Controllers\V1\ProjectController;
use Illuminate\Support\Facades\Route;

Route::apiResource('projects', ProjectController::class);
