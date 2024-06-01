<?php

namespace Database\Factories;

use App\Models\Hobby;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hobby>
 */
class HobbyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=> $this->faker->realText(30),
            'beschreibung'=> $this->faker->realText(),
        ];
    }
}
