<?php

use App\Http\Controllers\LibraryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/books',[LibraryController::class, 'getBooksDistr']);
Route::get('/books/{id}',[LibraryController::class, 'getBookById']);
Route::post('/books', [LibraryController::class, 'createBook']);
Route::delete('/books/{id}', [LibraryController::class, 'deleteBook']);

