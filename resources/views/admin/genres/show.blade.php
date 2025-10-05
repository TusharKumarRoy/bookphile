@extends('admin.layout')

@section('title', 'View Genre')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.genres.index') }}" class="text-blue-600 hover:text-blue-500 mr-4">
                ← Back to Genres
            </a>
            <h2 class="text-2xl font-bold text-gray-900">{{ $genre->name }}</h2>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.genres.edit', $genre) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Genre
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Genre Info -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $genre->name }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $genre->books->count() }} {{ Str::plural('book', $genre->books->count()) }}</p>
            </div>
            
            <div class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">URL Slug</dt>
                    <dd class="text-sm text-gray-900 font-mono bg-gray-100 px-2 py-1 rounded">{{ $genre->slug }}</dd>
                </div>
                
                @if($genre->description)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="text-sm text-gray-700 mt-1">{{ $genre->description }}</dd>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Genre Statistics -->
        @if($genre->books->count() > 0)
            <div class="bg-white shadow rounded-lg p-6 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $genre->books->count() }}</div>
                        <div class="text-sm text-gray-500">Total Books</div>
                    </div>
                    
                    @if($genre->books->whereNotNull('average_rating')->count() > 0)
                        <div class="text-center pt-4 border-t border-gray-200">
                            <div class="text-2xl font-bold text-yellow-600">{{ number_format($genre->books->avg('average_rating'), 1) }}</div>
                            <div class="text-sm text-gray-500">Average Rating</div>
                        </div>
                    @endif
                    
                    @if($genre->books->sum('page_count') > 0)
                        <div class="text-center pt-4 border-t border-gray-200">
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($genre->books->sum('page_count')) }}</div>
                            <div class="text-sm text-gray-500">Total Pages</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
    
    <!-- Books in this Genre -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">Books in {{ $genre->name }}</h3>
                <div class="flex items-center space-x-4">
                    @if($genre->books->count() > 0)
                        <span class="text-sm text-gray-500">{{ $genre->books->count() }} {{ Str::plural('book', $genre->books->count()) }}</span>
                        <a href="{{ route('books.index', ['genre' => $genre->id]) }}" 
                           class="text-blue-600 hover:text-blue-500 text-sm font-medium" target="_blank">
                            View All in Public Site →
                        </a>
                    @endif
                </div>
            </div>
            
            @if($genre->books->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($genre->books as $book)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <div class="aspect-[3/4] bg-gray-100">
                                @if($book->cover_image)
                                    <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                                        <span class="text-white text-xs font-bold text-center px-2">{{ $book->title }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-medium text-gray-900 text-sm mb-1">
                                    <a href="{{ route('admin.books.show', $book) }}" class="hover:text-blue-600">
                                        {{ $book->title }}
                                    </a>
                                </h4>
                                <p class="text-xs text-gray-500 mb-2">by {{ $book->authors_string }}</p>
                                @if($book->publication_date)
                                    <p class="text-xs text-gray-500 mb-2">{{ $book->publication_date->format('Y') }}</p>
                                @endif
                                
                                <!-- Rating -->
                                @if($book->average_rating > 0)
                                    <div class="flex items-center text-xs text-gray-500 mb-2">
                                        <span class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($book->average_rating))
                                                    ⭐
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="ml-1">{{ number_format($book->average_rating, 1) }}</span>
                                    </div>
                                @endif
                                
                                <!-- Other Genres -->
                                @php
                                    $otherGenres = $book->genres->where('id', '!=', $genre->id);
                                @endphp
                                @if($otherGenres->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($otherGenres->take(2) as $otherGenre)
                                            <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                                {{ $otherGenre->name }}
                                            </span>
                                        @endforeach
                                        @if($otherGenres->count() > 2)
                                            <span class="text-xs text-gray-500">+{{ $otherGenres->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No books in this genre</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding a book to this genre.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.books.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Book
                        </a>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Popular Authors in this Genre -->
        @if($genre->books->count() > 0)
            @php
                $authors = $genre->books->flatMap(function($book) { return $book->authors; })
                                       ->groupBy('id')
                                       ->map(function($authorBooks) { 
                                           return [
                                               'author' => $authorBooks->first(),
                                               'book_count' => $authorBooks->count()
                                           ]; 
                                       })
                                       ->sortByDesc('book_count')
                                       ->take(6);
            @endphp
            
            @if($authors->count() > 0)
                <div class="bg-white shadow rounded-lg p-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Popular Authors in {{ $genre->name }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($authors as $authorData)
                            @php $author = $authorData['author']; @endphp
                            <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                                <div class="flex-shrink-0">
                                    @if($author->image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $author->image }}" alt="{{ $author->first_name }} {{ $author->last_name }}">
                                    @else
                                        <div class="h-10 w-10 bg-gray-300 rounded-full flex items-center justify-center">
                                            <span class="text-sm text-gray-600">👤</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.authors.show', $author) }}" class="hover:text-blue-600">
                                            {{ $author->first_name }} {{ $author->last_name }}
                                        </a>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $authorData['book_count'] }} {{ Str::plural('book', $authorData['book_count']) }} in this genre</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
        
        <!-- Metadata -->
        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Metadata</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="text-sm text-gray-900">{{ $genre->created_at->format('F j, Y \a\t g:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="text-sm text-gray-900">{{ $genre->updated_at->format('F j, Y \a\t g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection