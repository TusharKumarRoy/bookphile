@extends('admin.layout')

@section('title', 'Manage Books')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Books Management</h2>
        <a href="{{ route('admin.books.create') }}" 
           class="border border-black bg-white text-black font-bold py-2 px-4 rounded-lg inline-flex items-center hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Book
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
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

<!-- Search and Filters -->
<div class="bg-white shadow rounded-lg mb-6 hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Search & Filter Books</h3>
        
        <!-- Search Form -->
        <form method="GET" class="mb-6">
            <div class="flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search by title, author, ISBN, or description..." 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <button type="submit" 
                        class="border border-black bg-white text-black font-bold py-2 px-6 rounded-lg hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                    Search
                </button>
            </div>
        </form>
        
        <!-- Filter Options -->
        <div class="flex flex-wrap gap-4 items-center">
            <!-- Genre Filter -->
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="author" value="{{ request('author') }}">
                <label class="text-sm font-medium text-gray-700">Genre:</label>
                <select name="genre" onchange="this.form.submit()" 
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[160px]">
                    <option value="">All Genres</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            
            <!-- Author Filter -->
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="genre" value="{{ request('genre') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <label class="text-sm font-medium text-gray-700">Author:</label>
                <select name="author" onchange="this.form.submit()" 
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[180px]">
                    <option value="">All Authors</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                            {{ $author->getFullNameAttribute() }}
                        </option>
                    @endforeach
                </select>
            </form>
            
            <!-- Sort Options -->
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="genre" value="{{ request('genre') }}">
                <input type="hidden" name="author" value="{{ request('author') }}">
                <label class="text-sm font-medium text-gray-700">Sort by:</label>
                <select name="sort" onchange="this.form.submit()" 
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[220px]">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Date Added (Latest)</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating (High - Low)</option>
                    <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Publication Year (Latest)</option>
                    <option value="pages" {{ request('sort') == 'pages' ? 'selected' : '' }}>Page Count (High - Low)</option>
                </select>
            </form>
            
            <!-- Clear Filters -->
            @if(request()->hasAny(['search', 'genre', 'author', 'sort']))
                <a href="{{ route('admin.books.index') }}" 
                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Clear All Filters
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Actions -->
@if($books->count() > 0)
<div class="bg-white shadow rounded-lg mb-6 p-4" id="bulk-actions" style="display: none;">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-700">
                <span id="selected-count">0</span> books selected
            </span>
            <button type="button" onclick="selectAllBooks()" 
                    class="text-sm text-blue-600 hover:text-blue-800">
                Select All
            </button>
            <button type="button" onclick="clearSelection()" 
                    class="text-sm text-gray-600 hover:text-gray-800">
                Clear Selection
            </button>
        </div>
        <form id="bulk-delete-form" action="{{ route('admin.books.bulk-delete') }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmBulkDelete()" 
                    class="border border-red-600 bg-white text-red-600 font-bold py-2 px-4 rounded-lg hover:bg-red-600 hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Delete Selected
            </button>
        </form>
    </div>
</div>
@endif

<!-- Books Table -->
<div class="bg-white shadow overflow-hidden sm:rounded-md hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">All Books</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'genre', 'author']))
                        Showing {{ $books->total() }} books matching your filters
                    @else
                        Manage your book catalog
                    @endif
                </p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $books->total() }} {{ Str::plural('book', $books->total()) }} total
            </div>
        </div>
    </div>
    
    @if($books->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($books as $book)
                <li class="px-4 py-4 hover:bg-gray-50 hover:scale-[1.01] transition-all duration-200 cursor-pointer" 
                    onclick="toggleRowSelection({{ $book->id }}, event)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <!-- Checkbox -->
                            <div class="flex-shrink-0 mr-3">
                                <input type="checkbox" 
                                       id="book-{{ $book->id }}"
                                       value="{{ $book->id }}" 
                                       class="book-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       onchange="updateBulkActions()"
                                       onclick="event.stopPropagation()">
                            </div>
                            
                            <!-- Book Image -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.books.show', $book) }}" 
                                   onclick="event.stopPropagation()" 
                                   class="block">
                                    @if($book->cover_image)
                                        <img class="h-12 w-8 object-cover rounded hover:opacity-80 transition-opacity cursor-pointer" src="{{ $book->cover_image_url }}" alt="{{ $book->title }}">
                                    @else
                                        <div class="h-12 w-8 bg-gray-300 rounded flex items-center justify-center hover:bg-gray-400 transition-colors cursor-pointer">
                                            <span class="text-xs text-gray-600">📖</span>
                                        </div>
                                    @endif
                                </a>
                            </div>
                            
                            <!-- Book Details -->
                            <div class="min-w-0 flex-1 px-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('admin.books.show', $book) }}" 
                                           onclick="event.stopPropagation()" 
                                           class="hover:text-blue-600 transition-colors">
                                            <p class="text-sm font-medium text-gray-900 truncate hover:text-blue-600 cursor-pointer">{{ $book->title }}</p>
                                        </a>
                                        <p class="text-sm text-gray-500">
                                            by 
                                            @foreach($book->authors as $index => $author)
                                                @if($index > 0), @endif
                                                <a href="{{ route('admin.authors.show', $author) }}" 
                                                   onclick="event.stopPropagation()" 
                                                   class="hover:text-blue-600 transition-colors duration-200">
                                                    {{ $author->getFullNameAttribute() }}
                                                </a>
                                            @endforeach
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            @foreach($book->genres as $index => $genre)
                                                @if($index > 0), @endif
                                                <a href="{{ route('admin.genres.show', $genre) }}" 
                                                   onclick="event.stopPropagation()" 
                                                   class="hover:text-blue-600 transition-colors duration-200">
                                                    {{ $genre->name }}
                                                </a>
                                            @endforeach
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        @if($book->average_rating > 0)
                                            <div class="flex items-center justify-end mb-1">
                                                <span class="text-xs text-yellow-600 mr-1">★</span>
                                                <span class="text-xs text-gray-600">{{ number_format($book->average_rating, 1) }}</span>
                                                <span class="text-xs text-gray-400 ml-1">({{ $book->ratings_count }})</span>
                                            </div>
                                        @endif
                                        @if($book->isbn)
                                            <p class="text-xs text-gray-400">ISBN: {{ $book->isbn }}</p>
                                        @endif
                                        @if($book->page_count)
                                            <p class="text-xs text-gray-400">{{ $book->page_count }} pages</p>
                                        @endif
                                        <p class="text-xs text-gray-400">{{ $book->publication_year ?? 'Unknown year' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4" onclick="event.stopPropagation()">
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
            {{ $books->appends(request()->query())->links() }}
        </div>
    @else
        <div class="px-4 py-8 text-center">
            @if(request()->hasAny(['search', 'genre', 'author']))
                <p class="text-gray-500 mb-2">No books found matching your search criteria.</p>
                <a href="{{ route('admin.books.index') }}" class="text-blue-600 hover:text-blue-500">Clear filters</a>
                <span class="text-gray-500"> or </span>
                <a href="{{ route('admin.books.create') }}" class="text-blue-600 hover:text-blue-500">add a new book</a>
            @else
                <p class="text-gray-500">No books found. <a href="{{ route('admin.books.create') }}" class="text-blue-600 hover:text-blue-500">Add your first book</a></p>
            @endif
        </div>
    @endif
</div>

<script>
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.book-checkbox');
    const selectedCheckboxes = document.querySelectorAll('.book-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    selectedCount.textContent = selectedCheckboxes.length;
    
    if (selectedCheckboxes.length > 0) {
        bulkActions.style.display = 'block';
    } else {
        bulkActions.style.display = 'none';
    }
}

function toggleRowSelection(bookId, event) {
    // Check if the click was on a link, button, or the checkbox itself
    if (event.target.tagName.toLowerCase() === 'a' || 
        event.target.tagName.toLowerCase() === 'button' || 
        event.target.type === 'checkbox' ||
        event.target.closest('a') ||
        event.target.closest('button') ||
        event.target.closest('form')) {
        return; // Don't toggle if clicking on interactive elements
    }
    
    const checkbox = document.getElementById(`book-${bookId}`);
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        updateBulkActions();
    }
}

function selectAllBooks() {
    const checkboxes = document.querySelectorAll('.book-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateBulkActions();
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.book-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateBulkActions();
}

function confirmBulkDelete() {
    const selectedCheckboxes = document.querySelectorAll('.book-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    
    if (selectedCount === 0) {
        alert('Please select at least one book to delete.');
        return;
    }
    
    const confirmMessage = `Are you sure you want to delete ${selectedCount} selected book${selectedCount === 1 ? '' : 's'}? This action cannot be undone.`;
    
    if (confirm(confirmMessage)) {
        const form = document.getElementById('bulk-delete-form');
        
        // Add selected book IDs to the form
        selectedCheckboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_books[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });
        
        form.submit();
    }
}

// Initialize bulk actions visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
});
</script>
@endsection