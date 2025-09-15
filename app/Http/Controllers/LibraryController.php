<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Exception;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class LibraryController extends Controller
{
    private function authorsExist(array $ids) :bool //Проверим, существуют ли такие авторы
    {
        if (empty($ids)) {
            return false;
        }

        $existingCount = Author::whereIn('author_id', $ids)->count();

        return $existingCount === count($ids);
    }

    private function genresExist(array $ids) :bool//Проверим, существуют ли такие жанры
    {
        if (empty($ids)) {
            return false;
        }

        $existingCount = Genre::whereIn('genre_id', $ids)->count();

        return $existingCount === count($ids);
    }

    private function getGenreIds(array $genres) :array
    {
        if(!empty($genres)){
            $genres = Genre::whereIn('name', $genres)->get();

            return $genres->pluck('id')->toArray();
        }

        return [];
    }

    /*
     * Конец отладочных и вспомогательных запросов
     *
     * Начало рабочих запросов
     */

    public function getAllBooks()
    {
        try {
            $books = Book::with(['authors', 'genres'])->orderByDesc('book_id')->get();
            return json_encode($books);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBookById($id)
    {
        try{
            $book = Book::with(['authors', 'genres'])->find($id);
            if($book === null || $book->count() < 1)
                return response('', 404);
            return json_encode($book);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createBook(Request $request)
    {
        try{
            $validated = $request->validate([
                'title' => 'required|string|max:255|min:1',
                'published_at' => 'required|date|before_or_equal:today',
                'author_ids' => 'sometimes|array',
                'author_ids.*' => 'integer|exists:authors,author_id',
                'genre_ids' => 'sometimes|array',
                'genre_ids.*' => 'integer|exists:genres,genre_id'
            ]);

            $title = $validated['title'];
            $publishedAt = $validated['published_at'];

            $authorIds = $validated['author_ids'] ?? [];
            if(!$this->authorsExist($authorIds))
                return response()->json(['error' => 'Авторы не найдены, или не указаны'], 500);
            $genreIds = $validated['genre_ids'] ?? [];
            if(!$this->genresExist($genreIds))
                return response()->json(['error' => 'Жанры не найдены, или не указаны'], 500);

            $book = Book::create([
                'title' => $title,
                'published_at' => $publishedAt
            ]);

            $book->authors()->attach($authorIds);
            $book->genres()->attach($genreIds);

            return response()->json($book->load(['authors', 'genres']));
        }catch (ValidationException $e){
            return response()->json(['error' => $e->getMessage()], 444);
        }
        catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
