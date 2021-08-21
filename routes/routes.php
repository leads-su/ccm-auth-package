<?php

use Illuminate\Support\Facades\Route;

Route::post('authenticate', \ConsulConfigManager\Auth\Http\Controllers\AuthenticateController::class)
    ->name('domain.auth.authenticate');