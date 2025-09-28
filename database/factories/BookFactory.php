<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
        'title' => fake()->sentence(3),
        'isbn' => fake()->isbn13(),
        'description' => fake()->paragraphs(2, true),
        'page_count' => fake()->numberBetween(100, 1000),
        'publication_date' => fake()->dateTimeBetween('-50 years', 'now')->format('Y-m-d'),  
        'cover_image' => fake()->imageUrl(400, 600, 'books'), 
];
    }
}