<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;

Route::get('/', function () {
    return 'f';
});

Route::get('/page', [LibraryController::class, 'index']);
