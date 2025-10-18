<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GenreFactory extends Factory
{
    public function definition(): array
    {
        $genres = [
            'Fiction' => 'Literary works that describe imaginary events and characters.',
            'Mystery' => 'Stories involving crime, puzzles, or unexplained events.',
            'Romance' => 'Stories focused on love relationships and romantic entanglements.',
            'Science Fiction' => 'Fiction dealing with futuristic concepts and advanced technology.',
            'Fantasy' => 'Fiction featuring magical or supernatural elements.',
            'Thriller' => 'Fast-paced stories designed to keep readers in suspense.',
            'Horror' => 'Fiction intended to frighten, unsettle, or create suspense.',
            'Historical Fiction' => 'Fiction set in the past, recreating historical periods.',
            'Biography' => 'Written accounts of someone\'s life.',
            'Autobiography' => 'An account of a person\'s life written by that person.',
            'Self-Help' => 'Books designed to help readers improve their lives.',
            'Business' => 'Books focused on business practices and entrepreneurship.',
            'Health & Fitness' => 'Books about physical and mental health.',
            'Cooking' => 'Books containing recipes and cooking techniques.',
            'Travel' => 'Books about traveling and exploring different places.',
            'Philosophy' => 'Books exploring fundamental questions about existence.',
            'Psychology' => 'Books about human behavior and mental processes.',
            'Science' => 'Books explaining scientific concepts and discoveries.',
            'Technology' => 'Books about technological advances and computer science.',
            'History' => 'Books about past events and their significance.',
        ];

        $genreName = fake()->unique()->randomElement(array_keys($genres));
        
        return [
            'name' => $genreName,
            'description' => $genres[$genreName],
        ];
    }
}