<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        $bookTitles = [
            'The Silent Observer', 'Echoes of Tomorrow', 'The Forgotten Kingdom', 'Whispers in the Dark',
            'The Last Symphony', 'Shadow of the Moon', 'The Crystal Garden', 'Flames of Destiny',
            'The Hidden Truth', 'Ocean of Dreams', 'The Midnight Train', 'Secrets of the Past',
            'The Golden Hour', 'Rivers of Time', 'The Broken Mirror', 'Dance of Shadows',
            'The Eternal Quest', 'Wings of Freedom', 'The Sacred Grove', 'Voices from Beyond',
            'The Crimson Letter', 'Paths Untaken', 'The Silver Lining', 'Storms of Change',
            'The Ancient Code', 'Bridges to Nowhere', 'The Painted Sky', 'Songs of Solitude',
            'The Winding Road', 'Tides of Fortune', 'The Veiled Truth', 'Fragments of Light',
            'The Copper Crown', 'Maze of Mirrors', 'The Distant Shore', 'Threads of Fate',
            'The Shifting Sands', 'Mountain of Secrets', 'The Velvet Night', 'Carousel of Time',
            'The Iron Gate', 'Fields of Glory', 'The Sapphire Rose', 'Legends Reborn',
            'The Marble Tower', 'Valley of Whispers', 'The Scarlet Dawn', 'Chronicles of Hope',
            'The Emerald City', 'Seasons of Change', 'The Ruby Heart', 'Tales from Tomorrow',
            'The Diamond Ring', 'Crossing Boundaries', 'The Pearl Diver', 'Winds of Change'
        ];

        return [
            'title' => fake()->randomElement($bookTitles),
            'isbn' => fake()->isbn13(),
            'description' => fake()->paragraphs(2, true),
            'page_count' => fake()->numberBetween(150, 800),
            'publication_date' => fake()->dateTimeBetween('-30 years', 'now')->format('Y-m-d'),  
            'cover_image' => null,
        ];
    }
}