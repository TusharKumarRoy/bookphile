<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::withCount('books');
        
        // Add average rating calculation
        $query->withAvg('books', 'average_rating');
        
        // Search by author name (case-insensitive)
        if ($search = $request->get('search')) {
            $search = trim($search);
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }
        
        // Filter by genre (authors who have books in selected genre)
        if ($genreId = $request->get('genre')) {
            $query->whereHas('books.genres', function($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }
        
        // Sort options
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'book_count':
                // High to low (authors with more books first)
                $query->orderBy('books_count', 'desc');
                break;
            case 'avg_rating':
                // High to low (higher rated authors first, nulls last)
                $query->orderByRaw('books_avg_average_rating IS NULL, books_avg_average_rating DESC');
                break;
            case 'birth_year':
                // Young to old (newer birth years first, nulls last)
                $query->orderByRaw('birth_date IS NULL, birth_date DESC');
                break;
            case 'name':
            default:
                // A-Z (alphabetical order by first name, then last name)
                $query->orderBy('first_name', 'asc')->orderBy('last_name', 'asc');
                break;
        }
        
        $authors = $query->paginate(20);
        
        // Get genres for filter dropdown
        $genres = \App\Models\Genre::orderBy('name')->get();
        
        return view('admin.authors.index', compact('authors', 'genres'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'biography' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'death_date' => 'nullable|date|after:birth_date',
            'image' => 'nullable|url|max:2048',
        ]);

        Author::create($validated);

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author created successfully!');
    }

    public function show(Author $author)
    {
        $author->load('books');
        return view('admin.authors.show', compact('author'));
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'biography' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'death_date' => 'nullable|date|after:birth_date',
            'image' => 'nullable|url|max:2048',
        ]);

        $author->update($validated);

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author updated successfully!');
    }

    public function destroy(Author $author)
    {
        if ($author->books()->count() > 0) {
            return back()->with('error', 'Cannot delete author with associated books.');
        }

        $author->delete();

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        // Debug logging
        \Log::info('Bulk delete method called', ['request_data' => $request->all()]);
        
        $request->validate([
            'selected_authors' => 'required|array|min:1',
            'selected_authors.*' => 'exists:authors,id',
        ]);

        $authorIds = $request->input('selected_authors');
        
        // Check if any of the selected authors have books
        $authorsWithBooks = Author::whereIn('id', $authorIds)
            ->whereHas('books')
            ->get(['first_name', 'last_name'])
            ->map(function($author) {
                return $author->first_name . ' ' . $author->last_name;
            })
            ->toArray();

        if (!empty($authorsWithBooks)) {
            $authorsList = implode(', ', $authorsWithBooks);
            return back()->with('error', "Cannot delete the following authors because they have associated books: {$authorsList}");
        }

        // Delete the authors (only those without books)
        $deletedCount = Author::whereIn('id', $authorIds)->delete();

        return redirect()
            ->route('admin.authors.index')
            ->with('success', "Successfully deleted {$deletedCount} " . ($deletedCount === 1 ? 'author' : 'authors') . '!');
    }
}