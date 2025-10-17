@extends('admin.layout')

@section('title', 'Add New Book')

@section('content')
<div class="mb-8">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-900">Add New Book</h2>
    </div>
    @if(isset($selectedGenre) && $selectedGenre)
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        This book will be added to the <strong>{{ $selectedGenre->name }}</strong> genre automatically.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="bg-white shadow rounded-lg">
    <form action="{{ route('admin.books.store') }}" method="POST" class="space-y-6 p-6">
        @csrf
        
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('isbn')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="page_count" class="block text-sm font-medium text-gray-700">Page Count</label>
                <input type="number" name="page_count" id="page_count" value="{{ old('page_count') }}" min="1"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('page_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="publication_date" class="block text-sm font-medium text-gray-700">Publication Date</label>
                <input type="date" name="publication_date" id="publication_date" value="{{ old('publication_date') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('publication_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                <select name="language" id="language"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                    <option value="es" {{ old('language') == 'es' ? 'selected' : '' }}>Spanish</option>
                    <option value="fr" {{ old('language') == 'fr' ? 'selected' : '' }}>French</option>
                    <option value="de" {{ old('language') == 'de' ? 'selected' : '' }}>German</option>
                    <option value="it" {{ old('language') == 'it' ? 'selected' : '' }}>Italian</option>
                </select>
                @error('language')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image URL</label>
                <input type="url" name="cover_image" id="cover_image" value="{{ old('cover_image') }}"
                       placeholder="https://example.com/book-cover.jpg"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('cover_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
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
                        <option value="{{ $author->id }}" {{ collect(old('authors'))->contains($author->id) ? 'selected' : '' }}>
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
                        @php
                            $isSelected = collect(old('genres'))->contains($genre->id) || 
                                         (isset($selectedGenre) && $selectedGenre && $selectedGenre->id == $genre->id);
                        @endphp
                        <option value="{{ $genre->id }}" {{ $isSelected ? 'selected' : '' }}>
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
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Cancel
            </a>
            <button type="submit" 
                    class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Create Book
            </button>
        </div>
    </form>
</div>
@endsection