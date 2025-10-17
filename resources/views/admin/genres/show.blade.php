@extends('admin.layout')

@section('title', 'View Genre')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <h2 class="text-2xl font-bold text-gray-900">{{ $genre->name }}</h2>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.genres.edit', $genre) }}" 
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Edit Genre
            </a>
            <a href="{{ route('genres.show', $genre) }}" 
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200" 
               target="_blank">
                View Public Page
            </a>
            <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" class="inline" 
                  onsubmit="return confirm('Are you sure you want to delete this genre? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                    Delete Genre
                </button>
            </form>
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
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.books.create', ['genre' => $genre->id]) }}" 
                       class="inline-flex items-center px-3 py-2 border border-black bg-white text-black text-sm leading-4 font-medium rounded-md hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Book
                    </a>
                    <button type="button" 
                            class="inline-flex items-center px-3 py-2 border border-black bg-white text-black text-sm leading-4 font-medium rounded-md hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200"
                            onclick="openAddExistingBooksModal()">
                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Existing Books
                    </button>
                    @if($genre->books->count() > 0)
                        <span class="text-sm text-gray-500">{{ $genre->books->count() }} {{ Str::plural('book', $genre->books->count()) }}</span>
                    @endif
                </div>
            </div>
            
            @if($genre->books->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($genre->books as $book)
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <a href="{{ route('admin.books.show', $book) }}" class="block">
                                <div class="aspect-[3/4] bg-gray-100">
                                    @if($book->cover_image)
                                        <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center hover:opacity-90 transition-opacity">
                                            <span class="text-white text-xs font-bold text-center px-2">{{ $book->title }}</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
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
                                
                                <!-- All Genres -->
                                @if($book->genres->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($book->genres->take(3) as $bookGenre)
                                            <a href="{{ route('admin.genres.show', $bookGenre) }}" 
                                               class="inline-block bg-gray-100 hover:bg-green-100 text-gray-700 hover:text-green-800 text-xs px-2 py-1 rounded-full transition-colors duration-200">
                                                {{ $bookGenre->name }}
                                            </a>
                                        @endforeach
                                        @if($book->genres->count() > 3)
                                            <span class="text-xs text-gray-500">+{{ $book->genres->count() - 3 }} more</span>
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
                           class="inline-flex items-center px-4 py-2 border border-black bg-white text-black shadow-sm text-sm font-medium rounded-md hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
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
                            <a href="{{ route('admin.authors.show', $author) }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-colors duration-200">
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
                                    <p class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                        {{ $author->first_name }} {{ $author->last_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $authorData['book_count'] }} {{ Str::plural('book', $authorData['book_count']) }} in this genre</p>
                                </div>
                            </a>
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

<!-- Add Existing Books Modal -->
<div id="addExistingBooksModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Existing Books to {{ $genre->name }}</h3>
                <button type="button" onclick="closeAddExistingBooksModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('admin.genres.attach-books', $genre) }}" method="POST" id="attachBooksForm">
                @csrf
                <div class="mb-4">
                    <label for="book_search" class="block text-sm font-medium text-gray-700 mb-2">Search and select books:</label>
                    <input type="text" id="book_search" placeholder="Search books by title or author..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-md">
                    <div id="available_books" class="p-4">
                        <div class="text-center text-gray-500">Loading available books...</div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAddExistingBooksModal()" 
                            class="px-4 py-2 text-sm font-medium border border-black bg-white text-black rounded-md hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium border border-black bg-white text-black rounded-md hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                        Add Selected Books
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddExistingBooksModal() {
    document.getElementById('addExistingBooksModal').classList.remove('hidden');
    loadAvailableBooks();
}

function closeAddExistingBooksModal() {
    document.getElementById('addExistingBooksModal').classList.add('hidden');
    document.getElementById('book_search').value = '';
}

function loadAvailableBooks() {
    const container = document.getElementById('available_books');
    container.innerHTML = '<div class="text-center text-gray-500">Loading available books...</div>';
    
    // Fetch books that are not already in this genre
    fetch(`{{ route('admin.genres.available-books', $genre) }}`)
        .then(response => response.json())
        .then(data => {
            if (data.books && data.books.length > 0) {
                let html = '';
                data.books.forEach(book => {
                    html += `
                        <div class="book-item flex items-center p-3 hover:bg-gray-50 border-b border-gray-100" data-book-title="${book.title.toLowerCase()}" data-book-author="${book.authors_string.toLowerCase()}">
                            <div class="flex-shrink-0">
                                <input type="checkbox" name="book_ids[]" value="${book.id}" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        ${book.cover_image ? 
                                            `<img src="${book.cover_image}" alt="${book.title}" class="h-10 w-8 object-cover rounded mr-3">` :
                                            `<div class="h-10 w-8 bg-gray-300 rounded mr-3 flex items-center justify-center">
                                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>`
                                        }
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">${book.title}</p>
                                        <p class="text-xs text-gray-500">by ${book.authors_string}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="text-center text-gray-500 py-4">No books available to add to this genre.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<div class="text-center text-red-500 py-4">Error loading books. Please try again.</div>';
        });
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('book_search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const bookItems = document.querySelectorAll('#available_books .book-item');
            
            bookItems.forEach(item => {
                const title = item.getAttribute('data-book-title') || '';
                const author = item.getAttribute('data-book-author') || '';
                
                if (title.includes(searchTerm) || author.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});

// Close modal when clicking outside
document.getElementById('addExistingBooksModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddExistingBooksModal();
    }
});
</script>
@endsection