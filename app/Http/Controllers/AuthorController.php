<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::withCount('books');
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$searchTerm}%"]);
            });
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
                $query->orderBy('first_name')->orderBy('last_name');
        }
        
        $authors = $query->paginate(20);
        
        return view('authors.index', compact('authors'));
    }
    
    public function show(Author $author)
    {
        $author->load(['books.genres']);
        
        // Get books with pagination
        $books = $author->books()
                       ->with('genres')
                       ->latest()
                       ->paginate(12);
        
        return view('authors.show', compact('author', 'books'));
    }
}