@extends('admin.layout')

@section('title', 'View Book')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <h2 class="text-2xl font-bold text-gray-900">{{ $book->title }}</h2>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.books.edit', $book) }}" 
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Edit Book
            </a>
            <a href="{{ route('books.show', $book) }}" 
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200" 
               target="_blank">
                View Public Page
            </a>
            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline" 
                  onsubmit="return confirm('Are you sure you want to delete this book? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                    Delete Book
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Book Cover and Basic Info -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Cover Image -->
            <div class="aspect-[3/4] bg-gray-100">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                        <span class="text-white text-lg font-bold text-center px-4">{{ $book->title }}</span>
                    </div>
                @endif
            </div>
            
            <!-- Book Details -->
            <div class="p-6">
                <div class="space-y-4">
                    @if($book->isbn)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                            <dd class="text-sm text-gray-900">{{ $book->isbn }}</dd>
                        </div>
                    @endif
                    
                    @if($book->publication_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Publication Date</dt>
                            <dd class="text-sm text-gray-900">{{ $book->publication_date->format('F j, Y') }}</dd>
                        </div>
                    @endif
                    
                    @if($book->page_count)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pages</dt>
                            <dd class="text-sm text-gray-900">{{ number_format($book->page_count) }} pages</dd>
                        </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Language</dt>
                        <dd class="text-sm text-gray-900">{{ strtoupper($book->language) }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Description -->
        @if($book->description)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
            </div>
        @endif
        
        <!-- Authors -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Authors</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($book->authors as $author)
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-full object-cover" 
                                 src="{{ $author->image_url }}" 
                                 alt="{{ $author->first_name }} {{ $author->last_name }}"
                                 loading="lazy"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ substr($author->first_name, 0, 1) }}{{ substr($author->last_name, 0, 1) }}&color=ffffff&background=8b5cf6&size=256'">
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">
                                <a href="{{ route('admin.authors.show', $author) }}" class="hover:text-blue-600">
                                    {{ $author->first_name }} {{ $author->last_name }}
                                </a>
                            </p>
                            @if($author->birth_date)
                                <p class="text-xs text-gray-500">
                                    {{ $author->birth_date->format('Y') }}
                                    @if($author->death_date)
                                        - {{ $author->death_date->format('Y') }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Genres -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Genres</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($book->genres as $genre)
                    <a href="{{ route('admin.genres.show', $genre) }}" 
                       class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 hover:bg-purple-200">
                        {{ $genre->name }}
                    </a>
                @endforeach
            </div>
        </div>
        
        <!-- Reading Statistics -->
        @php
            $stats = $book->getReadingStats();
        @endphp
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reading Activity</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['want_to_read_count'] }}</div>
                    <div class="text-sm text-gray-500">Want to Read</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['currently_reading_count'] }}</div>
                    <div class="text-sm text-gray-500">Currently Reading</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['finished_reading_count'] }}</div>
                    <div class="text-sm text-gray-500">Finished Reading</div>
                </div>
            </div>
        </div>
        
        <!-- Ratings -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ratings</h3>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($book->average_rating))
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <span class="ml-2 text-sm text-gray-600">
                        {{ number_format($book->average_rating, 1) }} 
                        ({{ number_format($book->ratings_count) }} {{ Str::plural('rating', $book->ratings_count) }})
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Metadata -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Metadata</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="text-sm text-gray-900">{{ $book->created_at->format('F j, Y \a\t g:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="text-sm text-gray-900">{{ $book->updated_at->format('F j, Y \a\t g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection