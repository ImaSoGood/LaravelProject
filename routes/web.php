<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;

Route::get('/', function ()
{
    return 'f';
});

Route::get('/page', [LibraryController::class, 'index']);

Route::get('/all-books', [LibraryController::class, 'getAllBooks']);

Route::prefix('api')->group(function ()
{
    Route::get('/books',[LibraryController::class, 'getAllBooks']);
    Route::get('/books/{id}',[LibraryController::class, 'getBookById']);
    Route::post('/books', [LibraryController::class, 'createBook']);
});
