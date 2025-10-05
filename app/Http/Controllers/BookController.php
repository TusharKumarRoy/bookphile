<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['authors', 'genres']);
        
        // Search by title or author
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('authors', function($authorQuery) use ($search) {
                      $authorQuery->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by genre
        if ($genreId = $request->get('genre')) {
            $query->whereHas('genres', function($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }
        
        // Sort options
        $sort = $request->get('sort', 'title');
        switch ($sort) {
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'year':
                $query->orderBy('publication_year', 'desc');
                break;
            case 'pages':
                $query->orderBy('page_count', 'desc');
                break;
            default:
                $query->orderBy('title');
        }
        
        $books = $query->paginate(12);
        $genres = Genre::orderBy('name')->get();
        
        return view('books.index', compact('books', 'genres'));
    }
    
    public function show(Book $book)
    {
        $book->load(['authors', 'genres']);
        $relatedBooks = Book::whereHas('genres', function($q) use ($book) {
            $q->whereIn('genres.id', $book->genres->pluck('id'));
        })
        ->where('id', '!=', $book->id)
        ->with(['authors', 'genres'])
        ->limit(6)
        ->get();
        
        return view('books.show', compact('book', 'relatedBooks'));
    }
}