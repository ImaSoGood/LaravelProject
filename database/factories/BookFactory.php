<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    private function generateTitle()
    {
        $adjectives = ['Тайна', 'Великий', 'Последний', 'Забытый', 'Тёмный', 'Светлый', 'Древний', 'Новый',
            'Вечный', 'Скрытый', 'Ужасный', 'Интернет'];
        $nouns = ['замок', 'лес', 'океан', 'город', 'остров', 'компьютер', 'путь', 'воин', 'пророк', 'артефакт', 'ключ'];
        $subjects = ['времени', 'судьбы', 'огня', 'льда', 'ветра', 'звезд', 'луны', 'знания', 'солнца', 'тьмы', 'света'];

        if ($this->faker->boolean(70)) {
            return $this->faker->randomElement($adjectives) . ' ' .
                $this->faker->randomElement($nouns) . ' ' .
                $this->faker->randomElement($subjects);
        } else {
            return mb_convert_case($this->faker->randomElement($nouns), MB_CASE_TITLE, "UTF-8") . ' ' .
                $this->faker->randomElement($subjects);
        }
    }

    private function firstUpperLetter($str)
    {
        $char = mb_strtoupper(substr($str,0,2), "utf-8");
        $str[0] = $char[0];
        $str[1] = $char[1];
    }

    public function definition(): array
    {
        return [
            'title' => $this->generateTitle(),
            'published_at' => $this->faker->date(),
        ];
    }
}
