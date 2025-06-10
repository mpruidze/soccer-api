<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PlayerPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'age' => rand(18, 40),
            'country' => fake()->country(),
            'position' => fake()->randomElement(PlayerPosition::cases())->value,
        ];
    }
}
