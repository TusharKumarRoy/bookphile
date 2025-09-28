<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::withCount('books')
                        ->orderBy('last_name')
                        ->paginate(20);
        
        return view('admin.authors.index', compact('authors'));
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
}