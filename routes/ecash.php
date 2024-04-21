<?php

use Illuminate\Support\Facades\Route;
use Alhelwany\LaravelEcash\Http\Controllers\CallbackController;
use Alhelwany\LaravelEcash\Http\Controllers\RedirectController;

Route::post('/ecash/callback', CallbackController::class)->name('ecash.callback');
Route::get('/ecash/redirect', RedirectController::class)->name('ecash.redirect');
