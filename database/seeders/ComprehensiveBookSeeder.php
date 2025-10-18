<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;

class ComprehensiveBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Author::truncate();
        Book::truncate();
        Genre::truncate();
        \DB::table('author_book')->truncate();
        \DB::table('book_genre')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create genres first (20 genres)
        $this->command->info('Creating genres...');
        Genre::factory(20)->create();

        // Create authors (60 authors - this will allow multiple books per author)
        $this->command->info('Creating authors...');
        Author::factory(60)->create();

        // Create 100 books with relationships
        $this->command->info('Creating 100 books with relationships...');
        
        $genres = Genre::all();
        $authors = Author::all();

        for ($i = 1; $i <= 100; $i++) {
            $book = Book::factory()->create();

            // Assign 1-3 authors to each book
            $bookAuthors = $authors->random(rand(1, 3));
            $book->authors()->attach($bookAuthors->pluck('id'));

            // Assign 1-4 genres to each book
            $bookGenres = $genres->random(rand(1, 4));
            $book->genres()->attach($bookGenres->pluck('id'));

            if ($i % 10 == 0) {
                $this->command->info("Created {$i} books...");
            }
        }

        $this->command->info('✅ Successfully created:');
        $this->command->info('📚 100 Books');
        $this->command->info('👥 60 Authors (with profile images)');
        $this->command->info('🏷️ 20 Genres');
        $this->command->info('🔗 Author-Book and Book-Genre relationships');
    }
}