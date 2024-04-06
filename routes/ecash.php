<?php

use Illuminate\Support\Facades\Route;
use Organon\LaravelEcash\Http\Controllers\CallbackController;
use Organon\LaravelEcash\Http\Controllers\RedirectController;

Route::post('/ecash/callback', CallbackController::class)->name('ecash.callback');
Route::get('/ecash/redirect', RedirectController::class)->name('ecash.redirect');
