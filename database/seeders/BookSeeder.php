<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => '1984',
                'isbn' => '9780451524935',
                'description' => 'A dystopian social science fiction novel that follows the life of Winston Smith, a low-ranking member of the ruling Party in London, in the nation of Oceania, where the Party exercises total control over the masses.',
                'page_count' => 328,
                'published_date' => '1949-06-08',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg',
                'authors' => ['George Orwell'],
                'genres' => ['Fiction', 'Science Fiction'],
            ],
            [
                'title' => 'Animal Farm',
                'isbn' => '9780451526342',
                'description' => 'An allegorical novella that tells the story of a group of farm animals who rebel against their human farmer, hoping to create a society where animals can be equal, free, and happy.',
                'page_count' => 112,
                'published_date' => '1945-08-17',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780451526342-L.jpg',
                'authors' => ['George Orwell'],
                'genres' => ['Fiction', 'Fantasy'],
            ],
            [
                'title' => 'Pride and Prejudice',
                'isbn' => '9780141439518',
                'description' => 'A romantic novel that follows the emotional development of Elizabeth Bennet, who learns the error of making hasty judgments and comes to appreciate the difference between the superficial and the essential.',
                'page_count' => 432,
                'published_date' => '1813-01-28',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780141439518-L.jpg',
                'authors' => ['Jane Austen'],
                'genres' => ['Fiction', 'Romance'],
            ],
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'isbn' => '9780747532699',
                'description' => 'The first novel in the Harry Potter series and Rowling\'s debut novel, it follows Harry Potter, a young wizard who discovers his magical heritage and attends Hogwarts School of Witchcraft and Wizardry.',
                'page_count' => 223,
                'published_date' => '1997-06-26',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780747532699-L.jpg',
                'authors' => ['J.K. Rowling'],
                'genres' => ['Fiction', 'Fantasy'],
            ],
            [
                'title' => 'The Shining',
                'isbn' => '9780307743657',
                'description' => 'A horror novel that tells the story of Jack Torrance, an aspiring writer who becomes the winter caretaker at the isolated Overlook Hotel in Colorado, where he lives with his wife and clairvoyant son.',
                'page_count' => 447,
                'published_date' => '1977-01-28',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780307743657-L.jpg',
                'authors' => ['Stephen King'],
                'genres' => ['Fiction', 'Horror', 'Thriller'],
            ],
            [
                'title' => 'Murder on the Orient Express',
                'isbn' => '9780007119318',
                'description' => 'A detective novel featuring Hercule Poirot. The plot follows Poirot as he investigates the murder of an American businessman aboard the Orient Express train.',
                'page_count' => 256,
                'published_date' => '1934-01-01',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780007119318-L.jpg',
                'authors' => ['Agatha Christie'],
                'genres' => ['Fiction', 'Mystery'],
            ],
            [
                'title' => 'The Adventures of Tom Sawyer',
                'isbn' => '9780486400778',
                'description' => 'The story follows Tom Sawyer, a mischievous boy growing up in the fictional town of St. Petersburg, Missouri, along the Mississippi River during the 1840s.',
                'page_count' => 274,
                'published_date' => '1876-01-01',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780486400778-L.jpg',
                'authors' => ['Mark Twain'],
                'genres' => ['Fiction'],
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'isbn' => '9780061120084',
                'description' => 'The story takes place in the fictional town of Maycomb, Alabama, during the 1930s and is told through the eyes of Scout Finch as she watches her father defend a Black man falsely accused of rape.',
                'page_count' => 376,
                'published_date' => '1960-07-11',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780061120084-L.jpg',
                'authors' => ['Harper Lee'],
                'genres' => ['Fiction'],
            ],
            [
                'title' => 'The Great Gatsby',
                'isbn' => '9780743273565',
                'description' => 'Set in the Jazz Age on prosperous Long Island and in New York City, the novel provides a critical social history of Prohibition-era America during the Jazz Age.',
                'page_count' => 180,
                'published_date' => '1925-04-10',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg',
                'authors' => ['F. Scott Fitzgerald'],
                'genres' => ['Fiction'],
            ],
            [
                'title' => 'I Know Why the Caged Bird Sings',
                'isbn' => '9780345514400',
                'description' => 'The first in a seven-volume series of autobiographical works by Maya Angelou. It depicts her childhood and early teenage years in the American South during the 1930s.',
                'page_count' => 289,
                'published_date' => '1969-01-01',
                'language' => 'en',
                'cover_image_url' => 'https://covers.openlibrary.org/b/isbn/9780345514400-L.jpg',
                'authors' => ['Maya Angelou'],
                'genres' => ['Biography', 'Non-Fiction'],
            ],
        ];

        foreach ($books as $bookData) {
            // Create the book
            $book = Book::create([
            'title' => $bookData['title'],
            'isbn' => $bookData['isbn'],
            'description' => $bookData['description'],
            'page_count' => $bookData['page_count'],
            'publication_date' => $bookData['published_date'], 
            'language' => $bookData['language'],
            'cover_image' => $bookData['cover_image_url'], 
]);

            // Attach authors
            foreach ($bookData['authors'] as $authorName) {
                $author = Author::where('first_name', explode(' ', $authorName)[0])
                               ->where('last_name', implode(' ', array_slice(explode(' ', $authorName), 1)))
                               ->first();
                if ($author) {
                    $book->authors()->attach($author->id);
                }
            }

            // Attach genres
            foreach ($bookData['genres'] as $genreName) {
                $genre = Genre::where('name', $genreName)->first();
                if ($genre) {
                    $book->genres()->attach($genre->id);
                }
            }
        }
    }
}