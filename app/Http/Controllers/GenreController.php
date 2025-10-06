<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $query = Genre::withCount('books');
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'name');
        switch ($sortBy) {
            case 'books_count':
                $query->orderBy('books_count', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->orderBy('name');
        }
        
        $genres = $query->paginate(20);
        
        return view('genres.index', compact('genres'));
    }
    
    public function show(Genre $genre)
    {
        $genre->load('books.authors');
        
        // Get books with pagination and sorting
        $sortBy = request('sort', 'title');
        $query = $genre->books()->with('authors');
        
        switch ($sortBy) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'pages_asc':
                $query->orderBy('page_count');
                break;
            case 'pages_desc':
                $query->orderBy('page_count', 'desc');
                break;
            default:
                $query->orderBy('title');
        }
        
        $books = $query->paginate(12);
        
        return view('genres.show', compact('genre', 'books'));
    }
}