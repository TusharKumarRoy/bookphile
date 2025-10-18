@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Discover Amazing Authors</h1>
                <p class="text-xl text-gray-600 mb-8">Explore authors and discover their incredible works</p>
                
                <!-- Search Form -->
                <form method="GET" class="max-w-2xl mx-auto">
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search by author name..." 
                            class="flex-1 px-4 py-3 rounded-l-lg border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                        <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 rounded-r-lg font-semibold transition-colors">
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
                        <select name="sort" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="books_count" {{ request('sort') == 'books_count' ? 'selected' : '' }}>Number of Books</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Recently Added</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        </select>
                    </form>
                </div>
                
                <p class="text-sm text-gray-500">{{ $authors->total() }} authors found</p>
            </div>
        </div>
        
        <!-- Authors Grid -->
        @if($authors->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($authors as $author)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <a href="{{ route('authors.show', $author) }}" class="block">
                            <div class="p-6">
                                <!-- Author Avatar -->
                                <div class="flex items-center mb-4">
                                    <img class="w-16 h-16 rounded-full object-cover" 
                                         src="{{ $author->image_url }}" 
                                         alt="{{ $author->getFullNameAttribute() }}" 
                                         loading="lazy" 
                                         decoding="async"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ substr($author->first_name, 0, 1) }}{{ substr($author->last_name, 0, 1) }}&color=ffffff&background=10b981&size=256'">
                                    <div class="ml-4 flex-1">
                                        <h3 class="font-semibold text-gray-900 text-lg">{{ $author->getFullNameAttribute() }}</h3>
                                        <p class="text-gray-600 text-sm">
                                            {{ $author->books_count }} {{ Str::plural('book', $author->books_count) }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Author Info -->
                                <div class="space-y-2 text-sm text-gray-600">
                                    @if($author->birth_date)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>
                                                Born {{ $author->birth_date->format('Y') }}
                                                @if($author->isAlive())
                                                    ({{ $author->getAge() }} years old)
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    
                                    @if($author->death_date)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span>Died {{ $author->death_date->format('Y') }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($author->nationality)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span>{{ $author->nationality }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($author->biography)
                                    <p class="text-gray-700 text-sm mt-4 line-clamp-3">{{ $author->biography }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            {{ $authors->links() }}
        @else
            <div class="text-center py-16">
                <div class="text-gray-500 text-xl mb-4">No authors found</div>
                <p class="text-gray-400 mb-8">Try adjusting your search</p>
                <a href="{{ route('authors.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    View All Authors
                </a>
            </div>
        @endif
    </div>
</div>
@endsection