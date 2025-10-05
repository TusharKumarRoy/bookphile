@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Books</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_books']) }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('admin.books.index') }}" class="font-medium text-blue-600 hover:text-blue-500">Manage books</a>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Authors</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_authors']) }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('admin.authors.index') }}" class="font-medium text-green-600 hover:text-green-500">Manage authors</a>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Genres</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_genres']) }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('admin.genres.index') }}" class="font-medium text-purple-600 hover:text-purple-500">Manage genres</a>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_users']) }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <span class="font-medium text-indigo-600">{{ number_format($stats['books_being_read']) }} reading now</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.books.create') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md font-medium">
                    + Add New Book
                </a>
                <a href="{{ route('admin.authors.create') }}" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-md font-medium">
                    + Add New Author
                </a>
                <a href="{{ route('admin.genres.create') }}" 
                   class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-md font-medium">
                    + Add New Genre
                </a>
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-2 bg-white overflow-hidden shadow rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reading Activity</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['books_being_read']) }}</div>
                    <div class="text-sm text-gray-500">Currently Reading</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600">{{ number_format($stats['books_finished']) }}</div>
                    <div class="text-sm text-gray-500">Books Finished</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-600">{{ number_format($stats['books_being_read'] + $stats['books_finished']) }}</div>
                    <div class="text-sm text-gray-500">Total Activity</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Books -->
<div class="bg-white shadow rounded-lg mb-6">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Books</h3>
        <div class="flow-root">
            <ul class="-my-5 divide-y divide-gray-200">
                @forelse($stats['recent_books'] as $book)
                    <li class="py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $book->title }}</p>
                                <p class="text-sm text-gray-500">by {{ $book->authors_string }}</p>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $book->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-4 text-gray-500">No books yet</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection