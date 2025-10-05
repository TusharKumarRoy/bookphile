<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['authors', 'genres'])
                    ->latest()
                    ->paginate(20);
        
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $authors = Author::orderBy('last_name')->get();
        $genres = Genre::orderBy('name')->get();
        
        return view('admin.books.create', compact('authors', 'genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:17|unique:books',
            'description' => 'nullable|string',
            'page_count' => 'nullable|integer|min:1',
            'publication_date' => 'nullable|date',
            'language' => 'nullable|string|max:10',
            'cover_image' => 'nullable|url',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
        ]);

        $book = Book::create([
            'title' => $validated['title'],
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'] ?? null,
            'page_count' => $validated['page_count'] ?? null,
            'publication_date' => $validated['publication_date'] ?? null,
            'language' => $validated['language'] ?? 'en',
            'cover_image' => $validated['cover_image'] ?? null,
        ]);

        // Attach authors and genres
        $book->authors()->attach($validated['authors']);
        $book->genres()->attach($validated['genres']);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book created successfully!');
    }

    public function show(Book $book)
    {
        $book->load(['authors', 'genres', 'readingStatuses']);
        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $authors = Author::orderBy('last_name')->get();
        $genres = Genre::orderBy('name')->get();
        $book->load(['authors', 'genres']);
        
        return view('admin.books.edit', compact('book', 'authors', 'genres'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => ['nullable', 'string', 'max:17', Rule::unique('books')->ignore($book)],
            'description' => 'nullable|string',
            'page_count' => 'nullable|integer|min:1',
            'publication_date' => 'nullable|date',
            'language' => 'nullable|string|max:10',
            'cover_image' => 'nullable|url',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
        ]);

        $book->update([
            'title' => $validated['title'],
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'] ?? null,
            'page_count' => $validated['page_count'] ?? null,
            'publication_date' => $validated['publication_date'] ?? null,
            'language' => $validated['language'] ?? 'en',
            'cover_image' => $validated['cover_image'] ?? null,
        ]);

        // Sync authors and genres
        $book->authors()->sync($validated['authors']);
        $book->genres()->sync($validated['genres']);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        if ($book->readingStatuses()->count() > 0) {
            return back()->with('error', 'Cannot delete book that users have added to their lists.');
        }

        $book->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book deleted successfully!');
    }
}