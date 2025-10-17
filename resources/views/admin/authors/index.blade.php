@extends('admin.layout')

@section('title', 'Manage Authors')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Authors Management</h2>
        <a href="{{ route('admin.authors.create') }}" 
           class="border border-black bg-white text-black font-bold py-2 px-4 rounded-lg inline-flex items-center hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Author
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
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Authors</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $authors->total() }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="bg-white shadow rounded-lg mb-6 hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Search & Filter Authors</h3>
        
        <!-- Search Form -->
        <form method="GET" class="mb-6">
            <div class="flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search by author name..." 
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
            
            <!-- Sort Options -->
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="genre" value="{{ request('genre') }}">
                <label class="text-sm font-medium text-gray-700">Sort by:</label>
                <select name="sort" onchange="this.form.submit()" 
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[220px]">
                    <option value="name" {{ request('sort', 'name') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="book_count" {{ request('sort') == 'book_count' ? 'selected' : '' }}>Book Count (High - Low)</option>
                    <option value="avg_rating" {{ request('sort') == 'avg_rating' ? 'selected' : '' }}>Avg Rating (High - Low)</option>
                    <option value="birth_year" {{ request('sort') == 'birth_year' ? 'selected' : '' }}>Birth Year (Youngest First)</option>
                </select>
            </form>
            
            <!-- Clear Filters -->
            @if(request()->hasAny(['search', 'genre', 'sort']))
                <a href="{{ route('admin.authors.index') }}" 
                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Clear All Filters
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Actions -->
@if($authors->count() > 0)
<div class="bg-white shadow rounded-lg mb-6 p-4" id="bulk-actions" style="display: none;">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-700">
                <span id="selected-count">0</span> authors selected
            </span>
            <button type="button" onclick="selectAllAuthors()" 
                    class="text-sm text-blue-600 hover:text-blue-800">
                Select All
            </button>
            <button type="button" onclick="clearSelection()" 
                    class="text-sm text-gray-600 hover:text-gray-800">
                Clear Selection
            </button>
        </div>
        <form id="bulk-delete-form" action="{{ route('admin.authors.bulk-delete') }}" method="POST" class="inline">
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

<!-- Authors List -->
<div class="bg-white shadow overflow-hidden sm:rounded-md hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">All Authors</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'genre']))
                        Showing {{ $authors->total() }} authors matching your filters
                    @else
                        Manage your author database
                    @endif
                </p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $authors->total() }} {{ Str::plural('author', $authors->total()) }} total
            </div>
        </div>
    </div>
    
    @if($authors->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($authors as $author)
                <li class="px-4 py-4 hover:bg-gray-50 hover:scale-[1.01] transition-all duration-200 cursor-pointer" 
                    onclick="toggleRowSelection({{ $author->id }}, event)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <!-- Checkbox -->
                            <div class="flex-shrink-0 mr-3">
                                <input type="checkbox" 
                                       id="author-{{ $author->id }}"
                                       value="{{ $author->id }}" 
                                       class="author-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       onchange="updateBulkActions()"
                                       onclick="event.stopPropagation()">
                            </div>
                            
                            <!-- Author Image -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.authors.show', $author) }}" 
                                   onclick="event.stopPropagation()" 
                                   class="block">
                                    @if($author->image)
                                        <img class="h-12 w-12 rounded-full object-cover hover:opacity-80 transition-opacity cursor-pointer" 
                                             src="{{ $author->image }}" 
                                             alt="{{ $author->first_name }} {{ $author->last_name }}">
                                    @else
                                        <div class="h-12 w-12 bg-gray-300 rounded-full flex items-center justify-center hover:bg-gray-400 transition-colors cursor-pointer">
                                            <span class="text-lg text-gray-600">👤</span>
                                        </div>
                                    @endif
                                </a>
                            </div>
                            
                            <!-- Author Details -->
                            <div class="min-w-0 flex-1 px-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('admin.authors.show', $author) }}" 
                                           onclick="event.stopPropagation()" 
                                           class="hover:text-blue-600 transition-colors">
                                            <p class="text-sm font-medium text-gray-900 truncate hover:text-blue-600 cursor-pointer">
                                                {{ $author->first_name }} {{ $author->last_name }}
                                            </p>
                                        </a>
                                        @if($author->birth_date || $author->death_date)
                                            <p class="text-xs text-gray-400">
                                                @if($author->birth_date)
                                                    {{ $author->birth_date->format('Y') }}
                                                    @if($author->death_date)
                                                        - {{ $author->death_date->format('Y') }}
                                                    @endif
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @if($author->books_avg_average_rating)
                                            <div class="flex items-center justify-end mb-1">
                                                <span class="text-xs text-yellow-600 mr-1">★</span>
                                                <span class="text-xs text-gray-600">{{ number_format($author->books_avg_average_rating, 1) }}</span>
                                            </div>
                                        @endif
                                        <p class="text-xs text-gray-400">{{ $author->books_count }} {{ Str::plural('book', $author->books_count) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4" onclick="event.stopPropagation()">
                            <a href="{{ route('admin.authors.show', $author) }}" 
                               class="text-blue-600 hover:text-blue-900 text-sm font-medium">View</a>
                            <a href="{{ route('admin.authors.edit', $author) }}" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                            <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 text-sm font-medium"
                                        onclick="return confirm('Are you sure you want to delete this author?')">
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
            {{ $authors->appends(request()->query())->links() }}
        </div>
    @else
        <div class="px-4 py-8 text-center">
            @if(request()->hasAny(['search', 'genre']))
                <p class="text-gray-500 mb-2">No authors found matching your search criteria.</p>
                <a href="{{ route('admin.authors.index') }}" class="text-blue-600 hover:text-blue-500">Clear filters</a>
                <span class="text-gray-500"> or </span>
                <a href="{{ route('admin.authors.create') }}" class="text-blue-600 hover:text-blue-500">add a new author</a>
            @else
                <p class="text-gray-500">No authors found. <a href="{{ route('admin.authors.create') }}" class="text-blue-600 hover:text-blue-500">Add your first author</a></p>
            @endif
        </div>
    @endif
</div>

<script>
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.author-checkbox');
    const selectedCheckboxes = document.querySelectorAll('.author-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    selectedCount.textContent = selectedCheckboxes.length;
    
    if (selectedCheckboxes.length > 0) {
        bulkActions.style.display = 'block';
    } else {
        bulkActions.style.display = 'none';
    }
}

function toggleRowSelection(authorId, event) {
    // Check if the click was on a link, button, or the checkbox itself
    if (event.target.tagName.toLowerCase() === 'a' || 
        event.target.tagName.toLowerCase() === 'button' || 
        event.target.type === 'checkbox' ||
        event.target.closest('a') ||
        event.target.closest('button') ||
        event.target.closest('form')) {
        return; // Don't toggle if clicking on interactive elements
    }
    
    const checkbox = document.getElementById(`author-${authorId}`);
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        updateBulkActions();
    }
}

function selectAllAuthors() {
    const checkboxes = document.querySelectorAll('.author-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateBulkActions();
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.author-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateBulkActions();
}

function confirmBulkDelete() {
    const selectedCheckboxes = document.querySelectorAll('.author-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    
    if (selectedCount === 0) {
        alert('Please select at least one author to delete.');
        return;
    }
    
    const confirmMessage = `Are you sure you want to delete ${selectedCount} selected author${selectedCount === 1 ? '' : 's'}? This action cannot be undone.`;
    
    if (confirm(confirmMessage)) {
        const form = document.getElementById('bulk-delete-form');
        
        // Debug: Log the form action
        console.log('Form action:', form.action);
        
        // Add selected author IDs to the form
        selectedCheckboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_authors[]';
            input.value = checkbox.value;
            form.appendChild(input);
            console.log('Added author ID:', checkbox.value);
        });
        
        // Debug: Log before submit
        console.log('Submitting form...');
        form.submit();
    }
}

// Initialize bulk actions visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
});
</script>
@endsection