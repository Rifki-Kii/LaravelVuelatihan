<?php

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

Route::middleware([StartSession::class])->prefix('v1')->group(function () {
    require __DIR__.'/V1/auth.php';
    require __DIR__.'/V1/projects.php';
    require __DIR__.'/V1/tasks.php';
    require __DIR__.'/V1/comments.php';
    require __DIR__.'/V1/tags.php';
});
