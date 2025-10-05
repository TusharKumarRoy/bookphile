@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('books.index') }}" class="hover:text-blue-600">Books</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900">{{ $book->title }}</li>
            </ol>
        </nav>
        
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="md:flex">
                <!-- Book Cover -->
                <div class="md:w-1/3 lg:w-1/4">
                    <div class="aspect-[3/4] bg-gray-100 relative">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                                <span class="text-white text-lg font-bold text-center px-4">{{ $book->title }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Book Details -->
                <div class="md:w-2/3 lg:w-3/4 p-8">
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $book->title }}</h1>
                        <p class="text-xl text-gray-600 mb-4">
                            by {{ $book->authors->map(fn($author) => "{$author->first_name} {$author->last_name}")->implode(', ') }}
                        </p>
                        
                        <!-- Rating -->
                        <div class="flex items-center gap-2 mb-6">
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
                            <span class="text-lg font-semibold">{{ number_format($book->average_rating, 1) }}</span>
                            <span class="text-gray-500">({{ number_format($book->ratings_count) }} ratings)</span>
                        </div>
                    </div>
                    
                    <!-- Book Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 p-6 bg-gray-50 rounded-lg">
                        @if($book->publication_year)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Published</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $book->publication_year }}</dd>
                            </div>
                        @endif
                        
                        @if($book->page_count)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Pages</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ number_format($book->page_count) }}</dd>
                            </div>
                        @endif
                        
                        @if($book->isbn)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                                <dd class="text-sm font-mono text-gray-900">{{ $book->isbn }}</dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Genres</dt>
                            <dd class="flex flex-wrap gap-1 mt-1">
                                @foreach($book->genres as $genre)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach
                            </dd>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    @if($book->description)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3">
                        @auth
                            <button class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                Add to Reading List
                            </button>
                            <button class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                                Mark as Read
                            </button>
                            <button class="px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-lg transition-colors">
                                Add to Wishlist
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                                Login to Track This Book
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Books -->
        @if($relatedBooks->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">You might also like</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($relatedBooks as $relatedBook)
                        <a href="{{ route('books.show', $relatedBook) }}" class="group">
                            <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                                <div class="aspect-[3/4] bg-gray-100 relative overflow-hidden">
                                    @if($relatedBook->cover_image)
                                        <img src="{{ $relatedBook->cover_image }}" alt="{{ $relatedBook->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold text-center px-2">{{ $relatedBook->title }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1">{{ $relatedBook->title }}</h3>
                                    <p class="text-gray-600 text-xs">{{ number_format($relatedBook->average_rating, 1) }} ⭐</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection