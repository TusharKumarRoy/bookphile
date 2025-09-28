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
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Books Being Read</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['books_being_read']) }}</dd>
                    </dl>
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