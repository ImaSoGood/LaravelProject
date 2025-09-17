<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorsSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Author::factory()->count(100)->create();
    }
}
