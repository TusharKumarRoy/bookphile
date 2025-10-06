@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('authors.index') }}" class="inline-flex items-center text-green-600 hover:text-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Authors
            </a>
        </div>
        
        <!-- Author Profile -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-start gap-8">
                <!-- Author Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 bg-gradient-to-br from-green-400 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-4xl">
                        {{ strtoupper(substr($author->first_name, 0, 1) . substr($author->last_name, 0, 1)) }}
                    </div>
                </div>
                
                <!-- Author Info -->
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $author->getFullNameAttribute() }}</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="space-y-3">
                            @if($author->birth_date)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>
                                        Born {{ $author->birth_date->format('F j, Y') }}
                                        @if($author->isAlive())
                                            ({{ $author->getAge() }} years old)
                                        @endif
                                    </span>
                                </div>
                            @endif
                            
                            @if($author->death_date)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span>Died {{ $author->death_date->format('F j, Y') }}</span>
                                </div>
                            @endif
                            
                            @if($author->nationality)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $author->nationality }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="md:col-span-2">
                            @if($author->biography)
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Biography</h3>
                                <p class="text-gray-700 leading-relaxed">{{ $author->biography }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="flex items-center gap-6 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>{{ $author->books->count() }} {{ Str::plural('Book', $author->books->count()) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Books Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Books by {{ $author->getFullNameAttribute() }}</h2>
                <span class="text-sm text-gray-500">{{ $books->total() }} books</span>
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
                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold text-center px-2">{{ $book->title }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Book Info -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2">{{ $book->title }}</h3>
                                    
                                    <!-- Publication Year -->
                                    @if($book->publication_year)
                                        <p class="text-gray-600 text-xs mb-2">{{ $book->publication_year }}</p>
                                    @endif
                                    
                                    <!-- Genres -->
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($book->genres->take(2) as $genre)
                                            <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
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
                <div class="text-center py-12">
                    <div class="text-gray-500 text-lg mb-4">No books found</div>
                    <p class="text-gray-400">This author doesn't have any books in our collection yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection