<?php

use Illuminate\Support\Facades\Route;

Route::post('authenticate', \ConsulConfigManager\Auth\Http\Controllers\AuthenticateController::class)
    ->name('domain.auth.authenticate');

Route::get('user', \ConsulConfigManager\Auth\Http\Controllers\UserController::class)
    ->middleware('auth:sanctum')
    ->name('domain.auth.user');

Route::post('logout', \ConsulConfigManager\Auth\Http\Controllers\LogoutController::class)
    ->middleware('auth:sanctum')
    ->name('domain.auth.logout');
