@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-700 text-white">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Explore Book Genres</h1>
                <p class="text-xl opacity-90 mb-8">Discover books by your favorite genres and find new ones to love</p>
                
                <!-- Search Form -->
                <form method="GET" class="max-w-2xl mx-auto">
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by genre name..." 
                            class="flex-1 px-4 py-3 rounded-l-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        >
                        <button type="submit" class="px-8 py-3 bg-purple-500 hover:bg-purple-600 rounded-r-lg font-semibold transition-colors">
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
                    <!-- Sort Options -->
                    <form method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <label class="text-sm font-medium text-gray-700">Sort by:</label>
                        <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="books_count" {{ request('sort') == 'books_count' ? 'selected' : '' }}>Number of Books</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Recently Added</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        </select>
                    </form>
                </div>
                
                <p class="text-sm text-gray-500">{{ $genres->total() }} genres found</p>
            </div>
        </div>
        
        <!-- Genres Grid -->
        @if($genres->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($genres as $genre)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <a href="{{ route('genres.show', $genre) }}" class="block">
                            <div class="p-6">
                                <!-- Genre Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-xl">{{ $genre->name }}</h3>
                                        <p class="text-gray-600 text-sm">
                                            {{ $genre->books_count }} {{ Str::plural('book', $genre->books_count) }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($genre->name, 0, 2)) }}</span>
                                    </div>
                                </div>
                                
                                <!-- Genre Description -->
                                @if($genre->description)
                                    <p class="text-gray-700 text-sm line-clamp-3 mb-4">{{ $genre->description }}</p>
                                @endif
                                
                                <!-- Popular Badge -->
                                @if($genre->books_count >= 3)
                                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Popular Genre
                                    </div>
                                @elseif($genre->books_count == 0)
                                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Coming Soon
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            {{ $genres->links() }}
        @else
            <div class="text-center py-16">
                <div class="text-gray-500 text-xl mb-4">No genres found</div>
                <p class="text-gray-400 mb-8">Try adjusting your search</p>
                <a href="{{ route('genres.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                    View All Genres
                </a>
            </div>
        @endif
    </div>
</div>
@endsection