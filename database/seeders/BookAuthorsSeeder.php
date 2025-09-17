<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookAuthorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = \App\Models\Book::all();
        $authors = \App\Models\Author::all();

        foreach ($books as $book) {
            $authorCount = rand(1, 4);
            $rndAuthors = $authors->random($authorCount);

            $book->authors()->attach($rndAuthors);
        }
    }
}
