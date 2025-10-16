<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            [
                'first_name' => 'George',
                'last_name' => 'Orwell',
                'nationality' => 'British',
                'biography' => 'Eric Arthur Blair, known by his pen name George Orwell, was an English novelist, essayist, journalist and critic. His work is characterised by lucid prose, biting social criticism, opposition to totalitarianism, and outspoken support of democratic socialism.',
                'birth_date' => '1903-06-25',
                'death_date' => '1950-01-21',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Austen',
                'nationality' => 'British',
                'biography' => 'Jane Austen was an English novelist known primarily for her six major novels, which interpret, critique and comment upon the British landed gentry at the end of the 18th century.',
                'birth_date' => '1775-12-16',
                'death_date' => '1817-07-18',
            ],
            [
                'first_name' => 'J.K.',
                'last_name' => 'Rowling',
                'nationality' => 'British',
                'biography' => 'Joanne Rowling, better known by her pen name J. K. Rowling, is a British author, philanthropist, producer, and screenwriter, best known for writing the Harry Potter fantasy series.',
                'birth_date' => '1965-07-31',
                'death_date' => null,
            ],
            [
                'first_name' => 'Stephen',
                'last_name' => 'King',
                'nationality' => 'American',
                'biography' => 'Stephen Edwin King is an American author of horror, supernatural fiction, suspense, crime, science-fiction, and fantasy novels.',
                'birth_date' => '1947-09-21',
                'death_date' => null,
            ],
            [
                'first_name' => 'Agatha',
                'last_name' => 'Christie',
                'nationality' => 'British',
                'biography' => 'Dame Agatha Mary Clarissa Christie was an English writer known for her sixty-six detective novels and fourteen short story collections, particularly those revolving around fictional detectives Hercule Poirot and Miss Jane Marple.',
                'birth_date' => '1890-09-15',
                'death_date' => '1976-01-12',
            ],
            [
                'first_name' => 'Mark',
                'last_name' => 'Twain',
                'nationality' => 'American',
                'biography' => 'Samuel Langhorne Clemens, known by his pen name Mark Twain, was an American writer, humorist, entrepreneur, publisher, and lecturer.',
                'birth_date' => '1835-11-30',
                'death_date' => '1910-04-21',
            ],
            [
                'first_name' => 'Harper',
                'last_name' => 'Lee',
                'nationality' => 'American',
                'biography' => 'Nelle Harper Lee was an American novelist best known for her 1960 novel To Kill a Mockingbird. It won the 1961 Pulitzer Prize and has become a classic of modern American literature.',
                'birth_date' => '1926-04-28',
                'death_date' => '2016-02-19',
            ],
            [
                'first_name' => 'F. Scott',
                'last_name' => 'Fitzgerald',
                'nationality' => 'American',
                'biography' => 'Francis Scott Key Fitzgerald was an American novelist, essayist, screenwriter, and short-story writer. He was best known for his novels depicting the flamboyance and excess of the Jazz Age.',
                'birth_date' => '1896-09-24',
                'death_date' => '1940-12-21',
            ],
            [
                'first_name' => 'Ernest',
                'last_name' => 'Hemingway',
                'nationality' => 'American',
                'biography' => 'Ernest Miller Hemingway was an American novelist, short-story writer, and journalist. His economical and understated style had a strong influence on 20th-century fiction.',
                'birth_date' => '1899-07-21',
                'death_date' => '1961-07-02',
            ],
            [
                'first_name' => 'Maya',
                'last_name' => 'Angelou',
                'nationality' => 'American',
                'biography' => 'Maya Angelou was an American poet, memoirist, and civil rights activist. She published seven autobiographies, three books of essays, several books of poetry, and is credited with a list of plays, movies, and television shows.',
                'birth_date' => '1928-04-04',
                'death_date' => '2014-05-28',
            ],
        ];

        foreach ($authors as $authorData) {
            Author::firstOrCreate(
                [
                    'first_name' => $authorData['first_name'],
                    'last_name' => $authorData['last_name']
                ],
                $authorData
            );
        }
    }
}