@extends('admin.layout')

@section('title', 'Manage Books')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Books Management</h2>
        <a href="{{ route('admin.books.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Book
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Books</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $books->total() }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Books Table -->
<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">All Books</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage your book catalog</p>
    </div>
    
    @if($books->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($books as $book)
                <li class="px-4 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="flex-shrink-0">
                                @if($book->cover_image)
                                    <img class="h-12 w-8 object-cover rounded" src="{{ $book->cover_image }}" alt="{{ $book->title }}">
                                @else
                                    <div class="h-12 w-8 bg-gray-300 rounded flex items-center justify-center">
                                        <span class="text-xs text-gray-600">📖</span>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1 px-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $book->title }}</p>
                                        <p class="text-sm text-gray-500">by {{ $book->authors_string }}</p>
                                        <p class="text-xs text-gray-400">{{ $book->genres_string }}</p>
                                    </div>
                                    <div class="text-right">
                                        @if($book->isbn)
                                            <p class="text-xs text-gray-400">ISBN: {{ $book->isbn }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400">{{ $book->page_count }} pages</p>
                                        <p class="text-xs text-gray-400">{{ $book->publication_year ?? 'Unknown year' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('admin.books.show', $book) }}" 
                               class="text-blue-600 hover:text-blue-900 text-sm font-medium">View</a>
                            <a href="{{ route('admin.books.edit', $book) }}" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                            <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 text-sm font-medium"
                                        onclick="return confirm('Are you sure you want to delete this book?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $books->links() }}
        </div>
    @else
        <div class="px-4 py-8 text-center">
            <p class="text-gray-500">No books found. <a href="{{ route('admin.books.create') }}" class="text-blue-600 hover:text-blue-500">Add your first book</a></p>
        </div>
    @endif
</div>
@endsection