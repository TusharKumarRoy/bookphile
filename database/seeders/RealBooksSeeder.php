<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Support\Str;

class RealBooksSeeder extends Seeder
{
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

        $this->command->info('Creating real genres...');
        $this->createGenres();

        $this->command->info('Creating real authors...');
        $this->createAuthors();

        $this->command->info('Creating real books...');
        $this->createBooks();

        $this->command->info('✅ Successfully created 30+ real books with authors and genres!');
    }

    private function createGenres()
    {
        $genres = [
            ['name' => 'Fiction', 'description' => 'Literary works that describe imaginary events and characters.'],
            ['name' => 'Fantasy', 'description' => 'Fiction featuring magical or supernatural elements.'],
            ['name' => 'Science Fiction', 'description' => 'Fiction dealing with futuristic concepts and advanced technology.'],
            ['name' => 'Mystery', 'description' => 'Stories involving crime, puzzles, or unexplained events.'],
            ['name' => 'Thriller', 'description' => 'Fast-paced stories designed to keep readers in suspense.'],
            ['name' => 'Romance', 'description' => 'Stories focused on love relationships and romantic entanglements.'],
            ['name' => 'Historical Fiction', 'description' => 'Fiction set in the past, recreating historical periods.'],
            ['name' => 'Young Adult', 'description' => 'Books aimed at teenage readers aged 12-18.'],
            ['name' => 'Classic Literature', 'description' => 'Timeless works of literary merit from past eras.'],
            ['name' => 'Contemporary Fiction', 'description' => 'Fiction set in the present day.'],
            ['name' => 'Adventure', 'description' => 'Stories featuring exciting journeys and exploits.'],
            ['name' => 'Horror', 'description' => 'Fiction intended to frighten, unsettle, or create suspense.'],
            ['name' => 'Biography', 'description' => 'Written accounts of someone\'s life.'],
            ['name' => 'Non-Fiction', 'description' => 'Factual writing based on real events and information.'],
            ['name' => 'Self-Help', 'description' => 'Books designed to help readers improve their lives.'],
        ];

        foreach ($genres as $genreData) {
            Genre::create($genreData);
        }
    }

    private function createAuthors()
    {
        $authors = [
            ['first_name' => 'J.K.', 'last_name' => 'Rowling', 'nationality' => 'British', 'birth_date' => '1965-07-31', 'biography' => 'British author best known for the Harry Potter fantasy series.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/J._K._Rowling_2010.jpg/256px-J._K._Rowling_2010.jpg'],
            ['first_name' => 'George', 'last_name' => 'Orwell', 'nationality' => 'British', 'birth_date' => '1903-06-25', 'death_date' => '1950-01-21', 'biography' => 'English novelist and essayist, journalist and critic.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/George_Orwell_press_photo.jpg/256px-George_Orwell_press_photo.jpg'],
            ['first_name' => 'Harper', 'last_name' => 'Lee', 'nationality' => 'American', 'birth_date' => '1926-04-28', 'death_date' => '2016-02-19', 'biography' => 'American novelist best known for To Kill a Mockingbird.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Harper_Lee_%281962%29.jpg/256px-Harper_Lee_%281962%29.jpg'],
            ['first_name' => 'F. Scott', 'last_name' => 'Fitzgerald', 'nationality' => 'American', 'birth_date' => '1896-09-24', 'death_date' => '1940-12-21', 'biography' => 'American novelist and short-story writer of the Jazz Age.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/17/F_Scott_Fitzgerald_1921.jpg/256px-F_Scott_Fitzgerald_1921.jpg'],
            ['first_name' => 'Jane', 'last_name' => 'Austen', 'nationality' => 'British', 'birth_date' => '1775-12-16', 'death_date' => '1817-07-18', 'biography' => 'English novelist known for her wit and social commentary.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/CassandraAusten-JaneAusten%28c.1810%29_hires.jpg/256px-CassandraAusten-JaneAusten%28c.1810%29_hires.jpg'],
            ['first_name' => 'Agatha', 'last_name' => 'Christie', 'nationality' => 'British', 'birth_date' => '1890-09-15', 'death_date' => '1976-01-12', 'biography' => 'English writer known for her detective novels.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/30/Agatha_Christie.png/256px-Agatha_Christie.png'],
            ['first_name' => 'Stephen', 'last_name' => 'King', 'nationality' => 'American', 'birth_date' => '1947-09-21', 'biography' => 'American author of horror, supernatural fiction, suspense, and fantasy novels.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Stephen_King%2C_Comicon.jpg/256px-Stephen_King%2C_Comicon.jpg'],
            ['first_name' => 'J.R.R.', 'last_name' => 'Tolkien', 'nationality' => 'British', 'birth_date' => '1892-01-03', 'death_date' => '1973-09-02', 'biography' => 'English writer, poet, and professor, best known for The Hobbit and The Lord of the Rings.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b4/Tolkien_1916.jpg/256px-Tolkien_1916.jpg'],
            ['first_name' => 'Ernest', 'last_name' => 'Hemingway', 'nationality' => 'American', 'birth_date' => '1899-07-21', 'death_date' => '1961-07-02', 'biography' => 'American novelist, short-story writer, and journalist.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/ErnestHemingway.jpg/256px-ErnestHemingway.jpg'],
            ['first_name' => 'Dan', 'last_name' => 'Brown', 'nationality' => 'American', 'birth_date' => '1964-06-22', 'biography' => 'American author best known for his thriller novels.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Dan_Brown_bookjacket_cropped.jpg/256px-Dan_Brown_bookjacket_cropped.jpg'],
            ['first_name' => 'Suzanne', 'last_name' => 'Collins', 'nationality' => 'American', 'birth_date' => '1962-08-10', 'biography' => 'American television writer and author, best known for The Hunger Games trilogy.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/Suzanne_Collins_David_Shankbone_2010.jpg/256px-Suzanne_Collins_David_Shankbone_2010.jpg'],
            ['first_name' => 'Gillian', 'last_name' => 'Flynn', 'nationality' => 'American', 'birth_date' => '1971-02-24', 'biography' => 'American author and screenwriter, known for psychological thrillers.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Gillian_Flynn_2014_%28cropped%29.jpg/256px-Gillian_Flynn_2014_%28cropped%29.jpg'],
            ['first_name' => 'Margaret', 'last_name' => 'Atwood', 'nationality' => 'Canadian', 'birth_date' => '1939-11-18', 'biography' => 'Canadian poet, novelist, literary critic, and essayist.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Margaret_Atwood_Eden_Mills_Writers_Festival_2006.jpg/256px-Margaret_Atwood_Eden_Mills_Writers_Festival_2006.jpg'],
            ['first_name' => 'Paulo', 'last_name' => 'Coelho', 'nationality' => 'Brazilian', 'birth_date' => '1947-08-24', 'biography' => 'Brazilian lyricist and novelist, best known for The Alchemist.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Paulo_Coelho_nrkbeta.jpg/256px-Paulo_Coelho_nrkbeta.jpg'],
            ['first_name' => 'Khaled', 'last_name' => 'Hosseini', 'nationality' => 'Afghan-American', 'birth_date' => '1965-03-04', 'biography' => 'Afghan-American novelist and physician.', 'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/Khaled_Hosseini_in_2007.jpg/256px-Khaled_Hosseini_in_2007.jpg'],
        ];

        foreach ($authors as $authorData) {
            Author::create($authorData);
        }
    }

    private function createBooks()
    {
        $books = [
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'isbn' => '9780747532699',
                'description' => 'The first book in the Harry Potter series about a young wizard\'s journey.',
                'page_count' => 223,
                'publication_date' => '1997-06-26',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780747532699-L.jpg',
                'authors' => ['J.K. Rowling'],
                'genres' => ['Fantasy', 'Young Adult', 'Fiction']
            ],
            [
                'title' => '1984',
                'isbn' => '9780452284234',
                'description' => 'A dystopian social science fiction novel about totalitarian control.',
                'page_count' => 328,
                'publication_date' => '1949-06-08',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780452284234-L.jpg',
                'authors' => ['George Orwell'],
                'genres' => ['Science Fiction', 'Classic Literature', 'Fiction']
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'isbn' => '9780061120084',
                'description' => 'A novel about racial injustice and childhood in the American South.',
                'page_count' => 376,
                'publication_date' => '1960-07-11',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780061120084-L.jpg',
                'authors' => ['Harper Lee'],
                'genres' => ['Classic Literature', 'Fiction', 'Historical Fiction']
            ],
            [
                'title' => 'The Great Gatsby',
                'isbn' => '9780743273565',
                'description' => 'A classic American novel set in the Jazz Age.',
                'page_count' => 180,
                'publication_date' => '1925-04-10',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg',
                'authors' => ['F. Scott Fitzgerald'],
                'genres' => ['Classic Literature', 'Fiction', 'Romance']
            ],
            [
                'title' => 'Pride and Prejudice',
                'isbn' => '9780141439518',
                'description' => 'A romantic novel about manners, upbringing, morality, and marriage.',
                'page_count' => 432,
                'publication_date' => '1813-01-28',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141439518-L.jpg',
                'authors' => ['Jane Austen'],
                'genres' => ['Classic Literature', 'Romance', 'Fiction']
            ],
            [
                'title' => 'Murder on the Orient Express',
                'isbn' => '9780062693662',
                'description' => 'A detective novel featuring Hercule Poirot.',
                'page_count' => 256,
                'publication_date' => '1934-01-01',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780062693662-L.jpg',
                'authors' => ['Agatha Christie'],
                'genres' => ['Mystery', 'Fiction', 'Classic Literature']
            ],
            [
                'title' => 'The Shining',
                'isbn' => '9780345806789',
                'description' => 'A horror novel about a family\'s winter caretaking at the Overlook Hotel.',
                'page_count' => 447,
                'publication_date' => '1977-01-28',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780345806789-L.jpg',
                'authors' => ['Stephen King'],
                'genres' => ['Horror', 'Fiction', 'Thriller']
            ],
            [
                'title' => 'The Hobbit',
                'isbn' => '9780547928227',
                'description' => 'A fantasy novel about Bilbo Baggins\' unexpected journey.',
                'page_count' => 366,
                'publication_date' => '1937-09-21',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780547928227-L.jpg',
                'authors' => ['J.R.R. Tolkien'],
                'genres' => ['Fantasy', 'Adventure', 'Classic Literature']
            ],
            [
                'title' => 'The Old Man and the Sea',
                'isbn' => '9780684801223',
                'description' => 'A short novel about an aging fisherman\'s epic battle with a giant marlin.',
                'page_count' => 127,
                'publication_date' => '1952-09-01',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780684801223-L.jpg',
                'authors' => ['Ernest Hemingway'],
                'genres' => ['Classic Literature', 'Fiction', 'Adventure']
            ],
            [
                'title' => 'The Da Vinci Code',
                'isbn' => '9780307474278',
                'description' => 'A mystery thriller involving secret societies and religious history.',
                'page_count' => 454,
                'publication_date' => '2003-03-18',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780307474278-L.jpg',
                'authors' => ['Dan Brown'],
                'genres' => ['Mystery', 'Thriller', 'Fiction']
            ],
            [
                'title' => 'The Hunger Games',
                'isbn' => '9780439023528',
                'description' => 'A dystopian novel about a televised fight to the death.',
                'page_count' => 374,
                'publication_date' => '2008-09-14',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780439023528-L.jpg',
                'authors' => ['Suzanne Collins'],
                'genres' => ['Young Adult', 'Science Fiction', 'Fiction']
            ],
            [
                'title' => 'Gone Girl',
                'isbn' => '9780307588364',
                'description' => 'A psychological thriller about a marriage gone terribly wrong.',
                'page_count' => 419,
                'publication_date' => '2012-06-05',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780307588364-L.jpg',
                'authors' => ['Gillian Flynn'],
                'genres' => ['Mystery', 'Thriller', 'Contemporary Fiction']
            ],
            [
                'title' => 'The Handmaid\'s Tale',
                'isbn' => '9780385490818',
                'description' => 'A dystopian novel about a totalitarian society.',
                'page_count' => 311,
                'publication_date' => '1985-08-17',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780385490818-L.jpg',
                'authors' => ['Margaret Atwood'],
                'genres' => ['Science Fiction', 'Fiction', 'Contemporary Fiction']
            ],
            [
                'title' => 'The Alchemist',
                'isbn' => '9780061122415',
                'description' => 'A philosophical novel about a young shepherd\'s journey to find treasure.',
                'page_count' => 163,
                'publication_date' => '1988-01-01',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780061122415-L.jpg',
                'authors' => ['Paulo Coelho'],
                'genres' => ['Fiction', 'Adventure', 'Self-Help']
            ],
            [
                'title' => 'The Kite Runner',
                'isbn' => '9781594631931',
                'description' => 'A story of friendship against the backdrop of Afghanistan\'s tumultuous history.',
                'page_count' => 371,
                'publication_date' => '2003-05-29',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9781594631931-L.jpg',
                'authors' => ['Khaled Hosseini'],
                'genres' => ['Historical Fiction', 'Fiction', 'Contemporary Fiction']
            ],
            [
                'title' => 'Animal Farm',
                'isbn' => '9780452284240',
                'description' => 'An allegorical novella about farm animals who rebel against their human farmer.',
                'page_count' => 95,
                'publication_date' => '1945-08-17',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780452284240-L.jpg',
                'authors' => ['George Orwell'],
                'genres' => ['Classic Literature', 'Fiction', 'Science Fiction']
            ],
            [
                'title' => 'Harry Potter and the Chamber of Secrets',
                'isbn' => '9780439064873',
                'description' => 'The second book in the Harry Potter series.',
                'page_count' => 341,
                'publication_date' => '1998-07-02',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780439064873-L.jpg',
                'authors' => ['J.K. Rowling'],
                'genres' => ['Fantasy', 'Young Adult', 'Fiction']
            ],
            [
                'title' => 'The Lord of the Rings: The Fellowship of the Ring',
                'isbn' => '9780547928210',
                'description' => 'The first volume of the epic fantasy trilogy.',
                'page_count' => 423,
                'publication_date' => '1954-07-29',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780547928210-L.jpg',
                'authors' => ['J.R.R. Tolkien'],
                'genres' => ['Fantasy', 'Adventure', 'Classic Literature']
            ],
            [
                'title' => 'Sense and Sensibility',
                'isbn' => '9780141439662',
                'description' => 'Jane Austen\'s first published novel about the Dashwood sisters.',
                'page_count' => 409,
                'publication_date' => '1811-10-30',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141439662-L.jpg',
                'authors' => ['Jane Austen'],
                'genres' => ['Classic Literature', 'Romance', 'Fiction']
            ],
            [
                'title' => 'IT',
                'isbn' => '9781501142970',
                'description' => 'A horror novel about a group of children terrorized by an entity.',
                'page_count' => 1138,
                'publication_date' => '1986-09-15',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9781501142970-L.jpg',
                'authors' => ['Stephen King'],
                'genres' => ['Horror', 'Fiction', 'Thriller']
            ],
            [
                'title' => 'And Then There Were None',
                'isbn' => '9780062073488',
                'description' => 'Agatha Christie\'s best-selling mystery novel about ten strangers on an island.',
                'page_count' => 264,
                'publication_date' => '1939-11-06',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780062073488-L.jpg',
                'authors' => ['Agatha Christie'],
                'genres' => ['Mystery', 'Fiction', 'Classic Literature']
            ],
            [
                'title' => 'Catching Fire',
                'isbn' => '9780439023498',
                'description' => 'The second book in The Hunger Games trilogy.',
                'page_count' => 391,
                'publication_date' => '2009-09-01',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780439023498-L.jpg',
                'authors' => ['Suzanne Collins'],
                'genres' => ['Young Adult', 'Science Fiction', 'Fiction']
            ],
            [
                'title' => 'Angels & Demons',
                'isbn' => '9780671027360',
                'description' => 'A mystery thriller involving antimatter and the Vatican.',
                'page_count' => 736,
                'publication_date' => '2000-05-01',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780671027360-L.jpg',
                'authors' => ['Dan Brown'],
                'genres' => ['Mystery', 'Thriller', 'Fiction']
            ],
            [
                'title' => 'For Whom the Bell Tolls',
                'isbn' => '9780684803357',
                'description' => 'A novel about an American fighting in the Spanish Civil War.',
                'page_count' => 471,
                'publication_date' => '1940-10-21',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780684803357-L.jpg',
                'authors' => ['Ernest Hemingway'],
                'genres' => ['Classic Literature', 'Fiction', 'Historical Fiction']
            ],
            [
                'title' => 'A Thousand Splendid Suns',
                'isbn' => '9781594489501',
                'description' => 'A story about two Afghan women whose lives become intertwined.',
                'page_count' => 372,
                'publication_date' => '2007-05-22',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9781594489501-L.jpg',
                'authors' => ['Khaled Hosseini'],
                'genres' => ['Historical Fiction', 'Fiction', 'Contemporary Fiction']
            ],
            [
                'title' => 'Oryx and Crake',
                'isbn' => '9780385721677',
                'description' => 'A dystopian novel about genetic engineering and environmental destruction.',
                'page_count' => 376,
                'publication_date' => '2003-05-06',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780385721677-L.jpg',
                'authors' => ['Margaret Atwood'],
                'genres' => ['Science Fiction', 'Fiction', 'Contemporary Fiction']
            ],
            [
                'title' => 'Sharp Objects',
                'isbn' => '9780307341549',
                'description' => 'A psychological thriller about a journalist investigating murders in her hometown.',
                'page_count' => 254,
                'publication_date' => '2006-09-26',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780307341549-L.jpg',
                'authors' => ['Gillian Flynn'],
                'genres' => ['Mystery', 'Thriller', 'Contemporary Fiction']
            ],
            [
                'title' => 'Emma',
                'isbn' => '9780141439587',
                'description' => 'A comedy of manners about a young woman who fancies herself a matchmaker.',
                'page_count' => 474,
                'publication_date' => '1815-12-23',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780141439587-L.jpg',
                'authors' => ['Jane Austen'],
                'genres' => ['Classic Literature', 'Romance', 'Fiction']
            ],
            [
                'title' => 'Carrie',
                'isbn' => '9780307743664',
                'description' => 'Stephen King\'s first published novel about a girl with telekinetic powers.',
                'page_count' => 199,
                'publication_date' => '1974-04-05',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780307743664-L.jpg',
                'authors' => ['Stephen King'],
                'genres' => ['Horror', 'Fiction', 'Thriller']
            ],
            [
                'title' => 'The Two Towers',
                'isbn' => '9780547928203',
                'description' => 'The second volume of The Lord of the Rings trilogy.',
                'page_count' => 352,
                'publication_date' => '1954-11-11',
                'cover_image' => 'https://covers.openlibrary.org/b/isbn/9780547928203-L.jpg',
                'authors' => ['J.R.R. Tolkien'],
                'genres' => ['Fantasy', 'Adventure', 'Classic Literature']
            ]
        ];

        foreach ($books as $bookData) {
            // Create the book
            $book = Book::create([
                'title' => $bookData['title'],
                'isbn' => $bookData['isbn'],
                'description' => $bookData['description'],
                'page_count' => $bookData['page_count'],
                'publication_date' => $bookData['publication_date'],
                'cover_image' => $bookData['cover_image'],
            ]);

            // Attach authors
            foreach ($bookData['authors'] as $authorName) {
                $nameParts = explode(' ', $authorName);
                $firstName = $nameParts[0];
                $lastName = end($nameParts);
                
                $author = Author::where('first_name', 'LIKE', '%' . $firstName . '%')
                               ->where('last_name', 'LIKE', '%' . $lastName . '%')
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