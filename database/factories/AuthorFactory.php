<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'biography' => fake()->paragraphs(3, true),
            'birth_date' => fake()->dateTimeBetween('-100 years', '-30 years')->format('Y-m-d'),
            'death_date' => fake()->optional(0.3)->dateTimeBetween('-30 years', 'now')?->format('Y-m-d'),
        ];
    }
}