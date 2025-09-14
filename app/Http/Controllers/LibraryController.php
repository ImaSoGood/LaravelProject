<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Exception;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index()
    {
        $books = Book::find(1);
        return json_encode($books);
    }

    public function getAllBooks()
    {
        try {
            $books = Book::with(['authors', 'genres'])->orderBy('title')->get();
            return response()->json($books);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
