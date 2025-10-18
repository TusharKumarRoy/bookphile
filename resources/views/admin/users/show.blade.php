@extends('admin.layout')

@section('title', 'View User')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <h2 class="text-2xl font-bold text-gray-900">{{ $user->getFullNameAttribute() }}</h2>
        </div>
        <div class="flex items-center space-x-4">
            @if(auth()->user()->isMasterAdmin() && auth()->user()->canManageUser($user))
                <a href="{{ route('admin.users.edit', $user) }}" class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                    Edit User
                </a>
            @endif
            <a href="{{ route('users.show', $user) }}" 
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200" 
               target="_blank">
                View Public Page
            </a>
            @if(auth()->user()->canManageUser($user))
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200"
                            onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        Delete User
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- User Profile -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Profile Photo -->
            <div class="aspect-square bg-gray-100">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->getFullNameAttribute() }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-purple-600 flex items-center justify-center">
                        <span class="text-white text-6xl font-bold">{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                    </div>
                @endif
            </div>
            
            <!-- User Details -->
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $user->getFullNameAttribute() }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $user->role === 'master_admin' ? 'bg-red-100 text-red-800' : 
                                   ($user->role === 'admin' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Status</dt>
                        <dd class="text-sm text-gray-900">
                            @if($user->email_verified_at)
                                <span class="text-green-600">✓ Verified on {{ $user->email_verified_at->format('M j, Y') }}</span>
                            @else
                                <span class="text-red-600">✗ Not verified</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="text-sm text-gray-900">{{ $user->created_at->format('F j, Y') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $user->updated_at->format('F j, Y') }}</dd>
                    </div>
                    
                    @if($user->bio)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bio</dt>
                            <dd class="text-sm text-gray-700 mt-1 leading-relaxed">{!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-blue-600 hover:text-blue-800 underline">$1</a>', e($user->bio))) !!}</dd>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Activity -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Reading Statistics -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reading Activity</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ $readingStats['want_to_read'] }}</div>
                    <div class="text-sm text-gray-500">Want to Read</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $readingStats['currently_reading'] }}</div>
                    <div class="text-sm text-gray-500">Currently Reading</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600">{{ $readingStats['finished_reading'] }}</div>
                    <div class="text-sm text-gray-500">Finished Reading</div>
                </div>
            </div>
            
            @if($readingStats['total_books'] > 0)
                <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                    <div class="text-lg font-medium text-gray-900">{{ $readingStats['total_books'] }} Total Books</div>
                </div>
            @endif
        </div>
        
        <!-- Current Reading List -->
        @if($user->readingStatuses->where('status', 'currently_reading')->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Currently Reading</h3>
                <div class="space-y-4">
                    @foreach($user->readingStatuses->where('status', 'currently_reading') as $readingStatus)
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <div class="flex-shrink-0">
                                @if($readingStatus->book->cover_image)
                                    <img class="h-16 w-12 object-cover rounded" src="{{ $readingStatus->book->cover_image_url }}" alt="{{ $readingStatus->book->title }}">
                                @else
                                    <div class="h-16 w-12 bg-gray-300 rounded flex items-center justify-center">
                                        <span class="text-xs text-gray-600">📖</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('admin.books.show', $readingStatus->book) }}" class="hover:text-blue-600">
                                        {{ $readingStatus->book->title }}
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-500">by {{ $readingStatus->book->authors_string }}</p>
                                @if($readingStatus->started_reading)
                                    <p class="text-xs text-gray-400">Started {{ $readingStatus->started_reading->format('M j, Y') }}</p>
                                @endif
                                @if($readingStatus->current_page && $readingStatus->book->page_count)
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>Progress</span>
                                            <span>{{ $readingStatus->current_page }} / {{ $readingStatus->book->page_count }} pages</span>
                                        </div>
                                        <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $readingStatus->getReadingProgress() }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Recent Reading Activity -->
        @if($user->readingStatuses->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">All Reading Activity</h3>
                <div class="space-y-3">
                    @foreach($user->readingStatuses->sortByDesc('updated_at')->take(10) as $readingStatus)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if($readingStatus->book->cover_image)
                                        <img class="h-12 w-8 object-cover rounded" src="{{ $readingStatus->book->cover_image_url }}" alt="{{ $readingStatus->book->title }}">
                                    @else
                                        <div class="h-12 w-8 bg-gray-300 rounded flex items-center justify-center">
                                            <span class="text-xs text-gray-600">📖</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.books.show', $readingStatus->book) }}" class="hover:text-blue-600">
                                            {{ $readingStatus->book->title }}
                                        </a>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $readingStatus->getStatusLabel() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        {{ $readingStatus->status === 'finished_reading' ? 'bg-green-100 text-green-800' : 
                                           ($readingStatus->status === 'currently_reading' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $readingStatus->getStatusLabel() }}
                                    </span>
                                    @if($readingStatus->is_favorite)
                                        <span class="text-red-500">❤️</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $readingStatus->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($user->readingStatuses->count() > 10)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">Showing 10 of {{ $user->readingStatuses->count() }} books</p>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No reading activity</h3>
                    <p class="mt-1 text-sm text-gray-500">This user hasn't added any books to their reading lists yet.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection