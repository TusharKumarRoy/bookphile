@extends('admin.layout')

@section('title', 'Manage Authors')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Authors Management</h2>
        <a href="{{ route('admin.authors.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Author
        </a>
    </div>
</div>

<!-- Statistics Card -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Authors</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $authors->total() }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Authors Table -->
<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">All Authors</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage your author database</p>
    </div>
    
    @if($authors->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($authors as $author)
                <li class="px-4 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="flex-shrink-0">
                                @if($author->image)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ $author->image }}" alt="{{ $author->first_name }} {{ $author->last_name }}">
                                @else
                                    <div class="h-12 w-12 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-lg text-gray-600">👤</span>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1 px-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $author->first_name }} {{ $author->last_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $author->books_count }} book(s)</p>
                                        @if($author->birth_date)
                                            <p class="text-xs text-gray-400">
                                                {{ $author->birth_date->format('Y') }} 
                                                @if($author->death_date)
                                                    - {{ $author->death_date->format('Y') }}
                                                @else
                                                    ({{ $author->getAge() }} years old)
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
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
            {{ $authors->links() }}
        </div>
    @else
        <div class="px-4 py-8 text-center">
            <p class="text-gray-500">No authors found. <a href="{{ route('admin.authors.create') }}" class="text-blue-600 hover:text-blue-500">Add your first author</a></p>
        </div>
    @endif
</div>
@endsection