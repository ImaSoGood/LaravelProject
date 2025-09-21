<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Exception;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class LibraryController extends Controller
{
    public function getBooksDistr(Request $request)//Распределитель функций обработки запроса (с параметрами/без параметров)
    {
        if ($request->hasAny(['title', 'author', 'genre'])) {
            return $this->getBooks($request);
        }

        return $this->getAllBooks();
    }

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

    private function bookExist($id)
    {
        if (empty($id)) {
            return false;
        }

        return Book::where('book_id', $id)->exists();
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
            return BookResource::collection($books);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBooks(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'author' => 'sometimes|string|max:100',
                'genre' => 'sometimes|string|max:50',
            ]);

            $title = $validated['title'] ?? null;
            $author = $validated['author'] ?? null;
            $genre = $validated['genre'] ?? null;

            $books = Book::with(['authors', 'genres'])
                ->when(!empty($title), function ($query) use ($title) {
                    return $query->whereRaw('LOWER(title) LIKE ?', ['%' . mb_strtolower($title, 'UTF-8') . '%']);
                })
                ->when(!empty($author), function ($query) use ($author) {
                    return $query->whereHas('authors', function ($q) use ($author) {
                        $q->whereRaw('LOWER(full_name) LIKE ?', ['%' . mb_strtolower($author, 'UTF-8') . '%']);
                    });
                })
                ->when(!empty($genre), function ($query) use ($genre) {
                    return $query->whereHas('genres', function ($q) use ($genre) {
                        $q->whereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower($genre, 'UTF-8') . '%']);
                    });
                })
                ->orderByDesc('book_id')
                ->get();
            return BookResource::collection($books);
        } catch (ValidationException $e){
            return response()->json(['error' => $e->getMessage()], 444);
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
            return new BookResource($book);
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

            return new BookResource($book->load(['authors', 'genres']));
        }catch (ValidationException $e){
            return response()->json(['error' => $e->getMessage()], 444);
        }
        catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function deleteBook($id)
    {
        try{
            if($this->bookExist($id)){
                $book = Book::find($id);
                $book->delete();
                return response()->json(['success' => 'Удалена запись с "book_id" = '.$id], 500);
            }
            else
                return response()->json(['error' => 'Не существует записи с "book_id" = '.$id], 500);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
