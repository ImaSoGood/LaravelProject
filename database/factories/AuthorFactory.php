<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Author>
 */
class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        $firstName = ['Владимир', 'Алексей', 'Сергей', 'Дмитрий', 'Эльдар', 'Александр', 'Данин', 'Аркадий', 'Марк', 'Вячеслав', 'Андрей'];
        $lastName = ['Ковалев', 'Ким', 'Извеков', 'Сотников', 'Подлужный', 'Картавый', 'Ненашев', 'Гацуленко', 'Толстой', 'Кузнецов', 'Брагин'];
        $patronymic = ['Владимирович', 'Алексеевич', 'Сергеевич', 'Дмитриевич', 'Эльдарович', 'Александрович', 'Данинович', 'Аркадьевич', 'Маркович', 'Вячеславович', 'Андреевич'];

        $fullName = $this->faker->randomElement($lastName). ' ' . $this->faker->randomElement($firstName). ' ' . $this->faker->randomElement($patronymic);

        return [
            'full_name' => $fullName
        ];
    }
}
