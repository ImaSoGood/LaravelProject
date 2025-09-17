<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenresSeeder extends Seeder
{
    /**
     * Вот тут создано с никакой целью, ибо данные жанров это 1 несоставное слово...
     * Я написал 20 жанров, и теперь они уникально добавляются
     */
    public function run(): void
    {
        \App\Models\Genre::factory()->count(20)->create();
    }
}
