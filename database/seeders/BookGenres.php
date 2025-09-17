<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookGenres extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = \App\Models\Book::all();
        $genres = \App\Models\Genre::all();

        foreach ($books as $book) {
            $genreCount = rand(1, 3);
            $rndGenres = $genres->random($genreCount);

            $book->genres()->attach($rndGenres);
        }
    }
}
