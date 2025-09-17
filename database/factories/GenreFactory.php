<?php

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genre>
 */
class GenreFactory extends Factory
{
    protected $model = Genre::class;

    public function definition(): array
    {
        $genres = [
            'Роман', 'Повесть', 'Рассказ', 'Поэма', 'Драма',
            'Комедия', 'Трагедия', 'Фантастика', 'Детектив', 'Приключения',
            'Ужасы', 'Фэнтези', 'Научная литература', 'Биография', 'Исторический',
            'Мистика', 'Триллер', 'Романтика', 'Юмор', 'Публицистика'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($genres),
        ];
    }
}
