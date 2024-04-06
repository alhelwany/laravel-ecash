<?php

use Illuminate\Support\Facades\Route;
use MhdGhaithAlhelwany\LaravelEcash\Http\Controllers\CallbackController;

Route::get('/ecash/callback', CallbackController::class)->name('ecash.callback');
