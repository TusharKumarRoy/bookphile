<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
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
}