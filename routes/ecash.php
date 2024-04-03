<?php

use Illuminate\Support\Facades\Route;

Route::get('/ecash/callback', function () {
    return 123;
})->name('ecash.callback');
