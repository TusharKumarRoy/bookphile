@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('genres.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Genres
            </a>
        </div>
        
        <!-- Genre Header -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <div class="flex items-start gap-6">
                <!-- Genre Icon -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-pink-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-3xl">{{ strtoupper(substr($genre->name, 0, 2)) }}</span>
                    </div>
                </div>
                
                <!-- Genre Info -->
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $genre->name }}</h1>
                    
                    @if($genre->description)
                        <p class="text-gray-700 text-lg leading-relaxed mb-6">{{ $genre->description }}</p>
                    @endif
                    
                    <!-- Stats -->
                    <div class="flex items-center gap-6 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>{{ $genre->books->count() }} {{ Str::plural('Book', $genre->books->count()) }}</span>
                        </div>
                        
                        @if($genre->books->count() > 0)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>{{ $genre->books->pluck('authors')->flatten()->unique('id')->count() }} {{ Str::plural('Author', $genre->books->pluck('authors')->flatten()->unique('id')->count()) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Books Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Books in {{ $genre->name }}</h2>
                <div class="flex items-center gap-4">
                    <!-- Sort Options -->
                    <form method="GET" class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Sort by:</label>
                        <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="pages_asc" {{ request('sort') == 'pages_asc' ? 'selected' : '' }}>Shortest First</option>
                            <option value="pages_desc" {{ request('sort') == 'pages_desc' ? 'selected' : '' }}>Longest First</option>
                        </select>
                    </form>
                    <span class="text-sm text-gray-500">{{ $books->total() }} books</span>
                </div>
            </div>
            
            @if($books->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 mb-8">
                    @foreach($books as $book)
                        <div class="bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 overflow-hidden group">
                            <a href="{{ route('books.show', $book) }}" class="block">
                                <!-- Book Cover -->
                                <div class="aspect-[3/4] bg-gray-200 relative overflow-hidden">
                                    @if($book->cover_image)
                                        <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold text-center px-2">{{ $book->title }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Book Info -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2">{{ $book->title }}</h3>
                                    <p class="text-gray-600 text-xs mb-2">
                                        by {{ $book->authors->pluck('first_name', 'last_name')->map(fn($first, $last) => "$first $last")->implode(', ') }}
                                    </p>
                                    
                                    <!-- Publication Year -->
                                    @if($book->publication_year)
                                        <p class="text-gray-500 text-xs mb-2">{{ $book->publication_year }}</p>
                                    @endif
                                    
                                    <!-- Page Count -->
                                    @if($book->page_count)
                                        <p class="text-gray-500 text-xs">{{ $book->page_count }} pages</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                {{ $books->links() }}
            @else
                <div class="text-center py-12">
                    <div class="text-gray-500 text-lg mb-4">No books found</div>
                    <p class="text-gray-400">This genre doesn't have any books in our collection yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection