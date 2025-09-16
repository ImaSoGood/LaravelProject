<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;


Route::prefix('api')->group(function ()
{
    Route::get('/books',[LibraryController::class, 'getBooksDistr']);
    Route::get('/books/{id}',[LibraryController::class, 'getBookById']);
    Route::post('/books', [LibraryController::class, 'createBook']);
    Route::delete('/books/{id}', [LibraryController::class, 'deleteBook']);
});
