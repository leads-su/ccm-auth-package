<?php

use Illuminate\Support\Facades\Route;

Route::post('authenticate', \ConsulConfigManager\Auth\Http\Controllers\AuthenticateController::class)
    ->name('domain.auth.authenticate');

Route::get('user', \ConsulConfigManager\Auth\Http\Controllers\UserController::class)
    ->name('domain.auth.user');
