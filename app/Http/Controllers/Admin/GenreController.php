<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::withCount('books')
                       ->orderBy('name')
                       ->paginate(20);
        
        return view('admin.genres.index', compact('genres'));
    }

    public function create()
    {
        return view('admin.genres.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:genres',
            'description' => 'nullable|string',
        ]);

        Genre::create($validated);

        return redirect()
            ->route('admin.genres.index')
            ->with('success', 'Genre created successfully!');
    }

    public function show(Genre $genre)
    {
        $genre->load('books');
        return view('admin.genres.show', compact('genre'));
    }

    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    public function update(Request $request, Genre $genre)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('genres')->ignore($genre)],
            'description' => 'nullable|string',
        ]);

        $genre->update($validated);

        return redirect()
            ->route('admin.genres.index')
            ->with('success', 'Genre updated successfully!');
    }

    public function destroy(Genre $genre)
    {
        if ($genre->books()->count() > 0) {
            return back()->with('error', 'Cannot delete genre with associated books.');
        }

        $genre->delete();

        return redirect()
            ->route('admin.genres.index')
            ->with('success', 'Genre deleted successfully!');
    }

    public function availableBooks(Genre $genre)
    {
        // Get all books that are not already associated with this genre
        $books = Book::whereDoesntHave('genres', function($query) use ($genre) {
            $query->where('genre_id', $genre->id);
        })
        ->with('authors')
        ->orderBy('title')
        ->get();

        // Format the books for the API response
        $formattedBooks = $books->map(function($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'authors_string' => $book->authors_string,
                'cover_image' => $book->cover_image,
            ];
        });

        return response()->json([
            'books' => $formattedBooks
        ]);
    }

    public function attachBooks(Request $request, Genre $genre)
    {
        $validated = $request->validate([
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id'
        ]);

        // Get books that are not already attached to this genre
        $newBookIds = collect($validated['book_ids'])->filter(function($bookId) use ($genre) {
            return !$genre->books()->where('book_id', $bookId)->exists();
        });

        if ($newBookIds->count() > 0) {
            $genre->books()->attach($newBookIds->toArray());
            
            $message = $newBookIds->count() === 1 
                ? 'Book added to genre successfully!' 
                : $newBookIds->count() . ' books added to genre successfully!';
                
            return redirect()
                ->route('admin.genres.show', $genre)
                ->with('success', $message);
        } else {
            return redirect()
                ->route('admin.genres.show', $genre)
                ->with('info', 'All selected books are already in this genre.');
        }
    }
}