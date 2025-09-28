<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            ['name' => 'Fiction', 'description' => 'Literary works created from the imagination'],
            ['name' => 'Non-Fiction', 'description' => 'Books based on real events and facts'],
            ['name' => 'Mystery', 'description' => 'Stories involving puzzles, crimes, or unexplained events'],
            ['name' => 'Romance', 'description' => 'Stories focused on love and relationships'],
            ['name' => 'Science Fiction', 'description' => 'Fiction dealing with futuristic concepts and technology'],
            ['name' => 'Fantasy', 'description' => 'Fiction involving magical or supernatural elements'],
            ['name' => 'Thriller', 'description' => 'Suspenseful stories designed to keep readers on edge'],
            ['name' => 'Horror', 'description' => 'Fiction intended to frighten, unsettle, or create suspense'],
            ['name' => 'Biography', 'description' => 'Account of someone\'s life written by someone else'],
            ['name' => 'Autobiography', 'description' => 'Account of a person\'s life written by themselves'],
            ['name' => 'History', 'description' => 'Books about past events and their significance'],
            ['name' => 'Philosophy', 'description' => 'Works exploring fundamental questions about existence'],
            ['name' => 'Psychology', 'description' => 'Books about the human mind and behavior'],
            ['name' => 'Self-Help', 'description' => 'Books designed to help readers improve their lives'],
            ['name' => 'Business', 'description' => 'Books about commerce, entrepreneurship, and management'],
            ['name' => 'Technology', 'description' => 'Books about computers, programming, and digital innovation'],
            ['name' => 'Travel', 'description' => 'Books about places, cultures, and travel experiences'],
            ['name' => 'Cooking', 'description' => 'Books with recipes and culinary techniques'],
            ['name' => 'Health', 'description' => 'Books about wellness, fitness, and medical topics'],
            ['name' => 'Poetry', 'description' => 'Collections of poems and poetic works'],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}