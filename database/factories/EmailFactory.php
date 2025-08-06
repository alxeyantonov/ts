<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->email(),
            'user_id' => User::factory(),
        ];
    }
}
