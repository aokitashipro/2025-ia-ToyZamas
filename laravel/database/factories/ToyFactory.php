<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Toy>
 */
class ToyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'information' => fake()->realText(50),
            'price' => fake()->numberBetween(300, 50000),
            'category_id' => fake()->numberBetween(1,10),
            'series_id' => fake()->numberBetween(1,10),
            'stock' => fake()->numberBetween(1,100),
            'release_date' => fake()->dateTimeBetween('-10 years', 'now'),
        ];
    }
}
