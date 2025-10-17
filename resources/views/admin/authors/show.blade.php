@extends('admin.layout')

@section('title', 'View Author')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <h2 class="text-2xl font-bold text-gray-900">{{ $author->first_name }} {{ $author->last_name }}</h2>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.authors.edit', $author) }}" 
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Edit Author
            </a>
            <a href="{{ route('authors.show', $author) }}" 
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200" 
               target="_blank">
                View Public Page
            </a>
            <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" class="inline" 
                  onsubmit="return confirm('Are you sure you want to delete this author? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                    Delete Author
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Author Photo and Basic Info -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Author Photo -->
            <div class="aspect-square bg-gray-100">
                @if($author->image)
                    <img src="{{ $author->image }}" alt="{{ $author->first_name }} {{ $author->last_name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-600 flex items-center justify-center">
                        <span class="text-white text-6xl">👤</span>
                    </div>
                @endif
            </div>
            
            <!-- Author Details -->
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $author->first_name }} {{ $author->last_name }}</h3>
                        @if($author->isAlive())
                            <p class="text-sm text-green-600 font-medium">Living Author</p>
                        @else
                            <p class="text-sm text-gray-500">Deceased</p>
                        @endif
                    </div>
                    
                    @if($author->birth_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Born</dt>
                            <dd class="text-sm text-gray-900">{{ $author->birth_date->format('F j, Y') }}</dd>
                        </div>
                    @endif
                    
                    @if($author->death_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Died</dt>
                            <dd class="text-sm text-gray-900">{{ $author->death_date->format('F j, Y') }}</dd>
                        </div>
                    @endif
                    
                    @if($author->getAge())
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                @if($author->isAlive())
                                    Age
                                @else
                                    Age at Death
                                @endif
                            </dt>
                            <dd class="text-sm text-gray-900">{{ $author->getAge() }} years old</dd>
                        </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Books Published</dt>
                        <dd class="text-sm text-gray-900">{{ $author->books->count() }} {{ Str::plural('book', $author->books->count()) }}</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Biography -->
        @if($author->biography)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Biography</h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $author->biography }}</p>
                </div>
            </div>
        @endif
        
        <!-- Books by this Author -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Books by {{ $author->first_name }} {{ $author->last_name }}</h3>
            
            @if($author->books->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($author->books as $book)
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
                                @if($book->publication_date)
                                    <p class="text-xs text-gray-500 mb-2">{{ $book->publication_date->format('Y') }}</p>
                                @endif
                                <div class="flex items-center text-xs text-gray-500">
                                    <span class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($book->average_rating))
                                                ⭐
                                            @endif
                                        @endfor
                                    </span>
                                    @if($book->average_rating > 0)
                                        <span class="ml-1">{{ number_format($book->average_rating, 1) }}</span>
                                    @endif
                                </div>
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @foreach($book->genres->take(2) as $genre)
                                        <span class="inline-block bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded-full">
                                            {{ $genre->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No books found for this author.</p>
                    <a href="{{ route('admin.books.create') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                        Add a book by this author
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Author Statistics -->
        @if($author->books->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing Statistics</h3>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ $author->books->count() }}</div>
                        <div class="text-sm text-gray-500">Total Books</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ number_format($author->books->avg('average_rating'), 1) }}</div>
                        <div class="text-sm text-gray-500">Average Rating</div>
                    </div>
                </div>
                
                @if($author->books->whereNotNull('publication_date')->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4 text-center text-sm">
                            <div>
                                <div class="font-medium text-gray-900">First Published</div>
                                <div class="text-gray-500">{{ $author->books->whereNotNull('publication_date')->min('publication_date')->format('Y') }}</div>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Latest Published</div>
                                <div class="text-gray-500">{{ $author->books->whereNotNull('publication_date')->max('publication_date')->format('Y') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        
        <!-- Metadata -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Metadata</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Added to System</dt>
                    <dd class="text-sm text-gray-900">{{ $author->created_at->format('F j, Y \a\t g:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="text-sm text-gray-900">{{ $author->updated_at->format('F j, Y \a\t g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection