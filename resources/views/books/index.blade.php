@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Discover Your Next Great Read</h1>
                <p class="text-xl opacity-90 mb-8">Explore thousands of books, track your reading, and connect with fellow readers</p>
                
                <!-- Search Form -->
                <form method="GET" class="max-w-2xl mx-auto">
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by title or author..." 
                            class="flex-1 px-4 py-3 rounded-l-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <button type="submit" class="px-8 py-3 bg-blue-500 hover:bg-blue-600 rounded-r-lg font-semibold transition-colors">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Filters Bar -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex flex-wrap gap-4 items-center justify-between">
                <div class="flex flex-wrap gap-4 items-center">
                    <!-- Genre Filter -->
                    <form method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <label class="text-sm font-medium text-gray-700">Genre:</label>
                        <select name="genre" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Genres</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    
                    <!-- Sort Options -->
                    <form method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="genre" value="{{ request('genre') }}">
                        <label class="text-sm font-medium text-gray-700">Sort by:</label>
                        <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                            <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Publication Year</option>
                            <option value="pages" {{ request('sort') == 'pages' ? 'selected' : '' }}>Page Count</option>
                        </select>
                    </form>
                </div>
                
                <p class="text-sm text-gray-500">{{ $books->total() }} books found</p>
            </div>
        </div>
        
        <!-- Books Grid -->
        @if($books->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 mb-8">
                @foreach($books as $book)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200 overflow-hidden group">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <!-- Book Cover -->
                            <div class="aspect-[3/4] bg-gray-100 relative overflow-hidden">
                                @if($book->cover_image)
                                    <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
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
                                
                                <!-- Rating -->
                                <div class="flex items-center gap-1 mb-2">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($book->average_rating))
                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-600">{{ number_format($book->average_rating, 1) }}</span>
                                </div>
                                
                                <!-- Genres -->
                                <div class="flex flex-wrap gap-1">
                                    @foreach($book->genres->take(2) as $genre)
                                        <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                                            {{ $genre->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            {{ $books->links() }}
        @else
            <div class="text-center py-16">
                <div class="text-gray-500 text-xl mb-4">No books found</div>
                <p class="text-gray-400 mb-8">Try adjusting your search or filters</p>
                <a href="{{ route('books.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    View All Books
                </a>
            </div>
        @endif
    </div>
</div>
@endsection