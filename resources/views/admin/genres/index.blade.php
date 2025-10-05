@extends('admin.layout')

@section('title', 'Manage Genres')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Genres Management</h2>
        <a href="{{ route('admin.genres.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Genre
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
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Genres</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $genres->count() }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Genres Grid -->
<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">All Genres</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage your book categories</p>
    </div>
    
    @if($genres->count() > 0)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 p-6">
            @foreach($genres as $genre)
                <div class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm hover:border-gray-400 hover:shadow-md">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $genre->name }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $genre->books_count ?? 0 }} books</p>
                            @if($genre->description)
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $genre->description }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-2">Slug: {{ $genre->slug }}</p>
                        </div>
                        <div class="flex flex-col space-y-1 ml-4">
                            <a href="{{ route('admin.genres.edit', $genre) }}" 
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                            <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 text-sm font-medium"
                                        onclick="return confirm('Are you sure you want to delete this genre?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="px-4 py-8 text-center">
            <p class="text-gray-500">No genres found. <a href="{{ route('admin.genres.create') }}" class="text-blue-600 hover:text-blue-500">Add your first genre</a></p>
        </div>
    @endif
</div>
@endsection