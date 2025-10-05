@extends('admin.layout')

@section('title', 'Edit Book')

@section('content')
<div class="mb-8">
    <div class="flex items-center">
        <a href="{{ route('admin.books.index') }}" class="text-blue-600 hover:text-blue-500 mr-4">
            ← Back to Books
        </a>
        <h2 class="text-2xl font-bold text-gray-900">Edit Book: {{ $book->title }}</h2>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <form action="{{ route('admin.books.update', $book) }}" method="POST" class="space-y-6 p-6">
        @csrf
        @method('PATCH')
        
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('isbn')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="page_count" class="block text-sm font-medium text-gray-700">Page Count</label>
                <input type="number" name="page_count" id="page_count" value="{{ old('page_count', $book->page_count) }}" min="1"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('page_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="publication_date" class="block text-sm font-medium text-gray-700">Publication Date</label>
                <input type="date" name="publication_date" id="publication_date" value="{{ old('publication_date', $book->publication_date?->format('Y-m-d')) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('publication_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                <select name="language" id="language"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="en" {{ old('language', $book->language) == 'en' ? 'selected' : '' }}>English</option>
                    <option value="es" {{ old('language', $book->language) == 'es' ? 'selected' : '' }}>Spanish</option>
                    <option value="fr" {{ old('language', $book->language) == 'fr' ? 'selected' : '' }}>French</option>
                    <option value="de" {{ old('language', $book->language) == 'de' ? 'selected' : '' }}>German</option>
                    <option value="it" {{ old('language', $book->language) == 'it' ? 'selected' : '' }}>Italian</option>
                </select>
                @error('language')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image URL</label>
                <input type="url" name="cover_image" id="cover_image" value="{{ old('cover_image', $book->cover_image) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('cover_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $book->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="authors" class="block text-sm font-medium text-gray-700">Authors *</label>
                <select name="authors[]" id="authors" multiple required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        style="height: 120px;">
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" 
                                {{ (collect(old('authors', $book->authors->pluck('id')))->contains($author->id)) ? 'selected' : '' }}>
                            {{ $author->first_name }} {{ $author->last_name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple authors</p>
                @error('authors')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="genres" class="block text-sm font-medium text-gray-700">Genres *</label>
                <select name="genres[]" id="genres" multiple required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        style="height: 120px;">
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" 
                                {{ (collect(old('genres', $book->genres->pluck('id')))->contains($genre->id)) ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple genres</p>
                @error('genres')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.books.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Update Book
            </button>
        </div>
    </form>
</div>
@endsection