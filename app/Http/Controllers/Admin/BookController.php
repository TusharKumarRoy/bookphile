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
    public function index(Request $request)
    {
        $query = Book::with(['authors', 'genres']);
        
        // Search by title, author, or ISBN
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
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
        
        // Filter by author
        if ($authorId = $request->get('author')) {
            $query->whereHas('authors', function($q) use ($authorId) {
                $q->where('authors.id', $authorId);
            });
        }
        
        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'title':
                $query->orderBy('title');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'year':
                $query->orderBy('publication_date', 'desc');
                break;
            case 'pages':
                $query->orderBy('page_count', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $books = $query->paginate(20);
        $genres = Genre::orderBy('name')->get();
        $authors = Author::all()->sortBy(function($author) {
            return $author->getFullNameAttribute();
        });
        
        return view('admin.books.index', compact('books', 'genres', 'authors'));
    }

    public function create(Request $request)
    {
        $authors = Author::orderBy('last_name')->get();
        $genres = Genre::orderBy('name')->get();
        
        // Get the pre-selected genre if provided
        $selectedGenre = null;
        if ($request->has('genre')) {
            $selectedGenre = Genre::find($request->get('genre'));
        }
        
        return view('admin.books.create', compact('authors', 'genres', 'selectedGenre'));
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
            'image_type' => 'required|in:url,file',
            'cover_image' => 'nullable|url',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
        ]);

        // Handle cover image based on type
        $coverImageValue = null;
        if ($request->input('image_type') === 'url' && $request->filled('cover_image')) {
            $coverImageValue = $validated['cover_image'];
        } elseif ($request->input('image_type') === 'file' && $request->hasFile('cover_image_file')) {
            // Store the uploaded file
            $imagePath = $request->file('cover_image_file')->store('book-covers', 'public');
            $coverImageValue = $imagePath;
        }

        $book = Book::create([
            'title' => $validated['title'],
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'] ?? null,
            'page_count' => $validated['page_count'] ?? null,
            'publication_date' => $validated['publication_date'] ?? null,
            'language' => $validated['language'] ?? 'en',
            'cover_image' => $coverImageValue,
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
            'image_type' => 'required|in:url,file',
            'cover_image' => 'nullable|url',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
        ]);

        // Handle cover image based on type
        $coverImageValue = $book->cover_image; // Keep existing image by default
        
        if ($request->input('image_type') === 'url') {
            if ($request->filled('cover_image')) {
                // Delete old file-based image if exists
                if ($book->cover_image && !filter_var($book->cover_image, FILTER_VALIDATE_URL)) {
                    if (file_exists(public_path('storage/' . $book->cover_image))) {
                        unlink(public_path('storage/' . $book->cover_image));
                    }
                }
                $coverImageValue = $validated['cover_image'];
            }
        } elseif ($request->input('image_type') === 'file') {
            if ($request->hasFile('cover_image_file')) {
                // Delete old file-based image if exists
                if ($book->cover_image && !filter_var($book->cover_image, FILTER_VALIDATE_URL)) {
                    if (file_exists(public_path('storage/' . $book->cover_image))) {
                        unlink(public_path('storage/' . $book->cover_image));
                    }
                }
                // Store new uploaded file
                $imagePath = $request->file('cover_image_file')->store('book-covers', 'public');
                $coverImageValue = $imagePath;
            }
        }

        $book->update([
            'title' => $validated['title'],
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'] ?? null,
            'page_count' => $validated['page_count'] ?? null,
            'publication_date' => $validated['publication_date'] ?? null,
            'language' => $validated['language'] ?? 'en',
            'cover_image' => $coverImageValue,
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

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_books' => 'required|array|min:1',
            'selected_books.*' => 'exists:books,id',
        ]);

        $bookIds = $request->input('selected_books');
        
        // Check if any of the selected books have reading statuses
        $booksWithStatuses = Book::whereIn('id', $bookIds)
            ->whereHas('readingStatuses')
            ->pluck('title')
            ->toArray();

        if (!empty($booksWithStatuses)) {
            $booksList = implode(', ', $booksWithStatuses);
            return back()->with('error', "Cannot delete the following books because users have added them to their lists: {$booksList}");
        }

        // Delete the books
        $deletedCount = Book::whereIn('id', $bookIds)->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('success', "Successfully deleted {$deletedCount} " . ($deletedCount === 1 ? 'book' : 'books') . '!');
    }
}