@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.modal-blur-backdrop {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.modal-content {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Prevent body scroll when modal is open */
body.modal-open {
    overflow: hidden;
}

/* Custom scrollbar for modal content */
.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Modal backdrop blur effect */
.modal-blur-backdrop {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

/* Prevent body scrolling when modal is open */
.modal-open {
    overflow: hidden;
    height: 100vh;
}

/* Enhanced modal animation */
.modal-content {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Modal backdrop animation */
.modal-backdrop {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Statistics card hover effects */
.stats-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
}

.stats-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stats-card:active {
    transform: translateY(-4px);
    transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <!-- Profile Header Section -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8 relative">
            <!-- Edit Profile Button (only show for own profile) - Top Right -->
            @auth
                @if(auth()->id() === $user->id)
                    <div class="absolute top-4 right-4">
                        <button onclick="openEditProfileModal()" 
                               class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 hover:border-gray-400 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Profile
                        </button>
                    </div>
                @endif
            @endauth
            
            <div class="p-6">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Profile Picture -->
                    <div class="flex-shrink-0">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                     alt="{{ $user->full_name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-4xl">
                                        {{ strtoupper(substr($user->full_name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex-1">
                        <!-- Full Name -->
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            {{ $user->full_name }}
                        </h1>
                        
                        <!-- Email -->
                        <p class="text-gray-600 text-lg mb-4">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            {{ $user->email }}
                        </p>
                        
                        <!-- Bio/About -->
                        @if($user->bio)
                            <div class="text-gray-700">
                                <h3 class="font-semibold text-gray-900 mb-2">About</h3>
                                <div class="leading-relaxed">{!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-blue-600 hover:text-blue-800 underline">$1</a>', e($user->bio))) !!}</div>
                            </div>
                        @else
                            <div class="text-gray-500 italic">
                                No bio available.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reading Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Want To Read Card -->
            <button onclick="openBooksModal('want_to_read')" 
                    class="stats-card bg-white rounded-lg shadow-sm p-6 text-left hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $readingStats['want_to_read'] }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Want To Read</h3>
                <p class="text-sm text-gray-600">Books on your reading list</p>
            </button>
            
            <!-- Currently Reading Card -->
            <button onclick="openBooksModal('currently_reading')" 
                    class="stats-card bg-white rounded-lg shadow-sm p-6 text-left hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-100 rounded-lg group-hover:bg-green-200 transition-colors">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $readingStats['currently_reading'] }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Currently Reading</h3>
                <p class="text-sm text-gray-600">Books you're reading now</p>
            </button>
            
            <!-- Finished Reading Card -->
            <button onclick="openBooksModal('finished_reading')" 
                    class="stats-card bg-white rounded-lg shadow-sm p-6 text-left hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $readingStats['finished_reading'] }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Finished Reading</h3>
                <p class="text-sm text-gray-600">Books you've completed</p>
            </button>
            
            <!-- Wishlist Card -->
            <button onclick="openBooksModal('wishlist')" 
                    class="stats-card bg-white rounded-lg shadow-sm p-6 text-left hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $wishlistCount }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Wishlist</h3>
                <p class="text-sm text-gray-600">Books you want to read</p>
            </button>
            
        </div>
        
        <!-- User Reviews and Ratings Section -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Recent Activity</h2>
                    <div class="text-sm text-gray-500">
                        {{ $recentActivity->count() }} recent items
                    </div>
                </div>
                
                @if($recentActivity->count() > 0)
                    <div class="space-y-6">
                        @foreach($recentActivity as $activity)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                @if($activity['type'] === 'review')
                                    <!-- Review Activity -->
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('books.show', $activity['data']->book) }}" 
                                               class="block w-16 h-20 bg-gray-100 rounded-lg overflow-hidden hover:opacity-80 transition-opacity cursor-pointer">
                                                @if($activity['data']->book->cover_image)
                                                    <img src="{{ $activity['data']->book->cover_image }}" 
                                                         alt="{{ $activity['data']->book->title }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold text-center px-1">
                                                            {{ Str::limit($activity['data']->book->title, 15) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </a>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <h3 class="font-semibold text-gray-900">
                                                        <a href="{{ route('books.show', $activity['data']->book) }}" 
                                                           class="hover:text-blue-600 transition-colors">
                                                            {{ $activity['data']->book->title }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-600">
                                                        by {{ $activity['data']->book->authors->map(function($author) { return $author->first_name . ' ' . $author->last_name; })->implode(', ') }}
                                                    </p>
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $activity['created_at']->diffForHumans() }}</span>
                                            </div>
                                            
                                            <!-- User's Rating for this book (if exists) -->
                                            @php
                                                $userRating = $user->ratings()->where('book_id', $activity['data']->book_id)->first();
                                            @endphp
                                            @if($userRating)
                                                <div class="flex items-center gap-2 mb-2">
                                                    <div class="flex text-yellow-400">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $userRating->rating)
                                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                                </svg>
                                                            @else
                                                                <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="text-sm text-gray-600">{{ $userRating->rating }}/5 stars</span>
                                                </div>
                                            @endif
                                            
                                            <!-- Review Text -->
                                            <div class="text-gray-700 text-sm mb-2">
                                                "{{ Str::limit($activity['data']->review_text, 150) }}"
                                            </div>
                                            
                                            <!-- Review Meta -->
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <a href="{{ route('books.show', $activity['data']->book) }}#reviews" 
                                                   class="flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                    </svg>
                                                    View Review
                                                </a>
                                                @if($activity['data']->is_spoiler)
                                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Contains Spoilers</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Rating Activity -->
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('books.show', $activity['data']->book) }}" 
                                               class="block w-16 h-20 bg-gray-100 rounded-lg overflow-hidden hover:opacity-80 transition-opacity cursor-pointer">
                                                @if($activity['data']->book->cover_image)
                                                    <img src="{{ $activity['data']->book->cover_image }}" 
                                                         alt="{{ $activity['data']->book->title }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-600 flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold text-center px-1">
                                                            {{ Str::limit($activity['data']->book->title, 15) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </a>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <h3 class="font-semibold text-gray-900">
                                                        <a href="{{ route('books.show', $activity['data']->book) }}" 
                                                           class="hover:text-blue-600 transition-colors">
                                                            {{ $activity['data']->book->title }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-600">
                                                        by {{ $activity['data']->book->authors->map(function($author) { return $author->first_name . ' ' . $author->last_name; })->implode(', ') }}
                                                    </p>
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $activity['created_at']->diffForHumans() }}</span>
                                            </div>
                                            
                                            <!-- Rating Stars -->
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $activity['data']->rating)
                                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-600">{{ $activity['data']->rating }}/5 stars</span>
                                            </div>
                                            
                                            <!-- Rating Meta -->
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <a href="{{ route('books.show', $activity['data']->book) }}#ratings" 
                                                   class="flex items-center gap-1 text-blue-600 hover:text-blue-800 transition-colors cursor-pointer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                    </svg>
                                                    View Rating
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No activity yet</h3>
                        <p class="text-gray-500 mb-4">Start rating and reviewing books to see your activity here.</p>
                        <a href="{{ route('books.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Browse Books
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</div>

<!-- Modals for book lists -->

<!-- Books Modal -->
<div id="books-modal" class="fixed inset-0 bg-gray-900 bg-opacity-60 modal-blur-backdrop overflow-y-auto h-full w-full hidden z-[9999] modal-backdrop">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-lg bg-white modal-content">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-bold text-gray-900" id="modal-title">Books</h3>
                <button onclick="closeBooksModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="py-4 max-h-96 overflow-y-auto" id="modal-content">
                <!-- Book content will be loaded here -->
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="text-gray-500 mt-2">Loading books...</p>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end pt-4 border-t">
                <button onclick="closeBooksModal()" 
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="edit-profile-modal" class="fixed inset-0 bg-gray-900 bg-opacity-60 modal-blur-backdrop overflow-y-auto h-full w-full hidden z-[9999] modal-backdrop">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-2xl rounded-lg bg-white modal-content max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b sticky top-0 bg-white z-10">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Edit Profile</h3>
                    <p class="text-sm text-gray-600">Update your profile information and settings</p>
                </div>
                <button onclick="closeEditProfileModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="py-6">
                <!-- Profile Form -->
                <form id="edit-profile-form" action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Profile Image Section -->
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center overflow-hidden">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                         alt="{{ $user->full_name }}" 
                                         class="w-full h-full object-cover"
                                         id="modal-profile-preview">
                                @else
                                    <span class="text-white text-2xl font-bold" id="modal-profile-initials">
                                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <label for="modal_profile_image" class="block text-sm font-medium text-gray-700 mb-2">
                                Profile Picture
                            </label>
                            <input type="file" 
                                   id="modal_profile_image" 
                                   name="profile_image" 
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF up to 2MB</p>
                        </div>
                    </div>

                    <!-- Name Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="modal_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="modal_first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name', $user->first_name) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter your first name">
                        </div>

                        <div>
                            <label for="modal_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="modal_last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name', $user->last_name) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Enter your last name">
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="modal_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="modal_email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter your email address">
                    </div>

                    <!-- Bio Field -->
                    <div>
                        <label for="modal_bio" class="block text-sm font-medium text-gray-700 mb-2">
                            Bio
                        </label>
                        <textarea id="modal_bio" 
                                  name="bio" 
                                  rows="4" 
                                  placeholder="Tell us a little about yourself and your reading interests..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('bio', $user->bio) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                    </div>

                    <!-- Required Fields Note -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-blue-800">
                                    <span class="font-semibold">Required fields</span> are marked with a red asterisk (<span class="text-red-500">*</span>). Please ensure all required fields are completed before saving.
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t sticky bottom-0 bg-white">
                <button onclick="closeEditProfileModal()" 
                        class="px-6 py-3 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button onclick="submitEditProfile()" 
                        class="px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openBooksModal(type) {
    const modal = document.getElementById('books-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content');
    
    // Set modal title based on type
    const titles = {
        'want_to_read': 'Want To Read',
        'currently_reading': 'Currently Reading', 
        'finished_reading': 'Finished Reading',
        'wishlist': 'Wishlist'
    };
    
    modalTitle.textContent = titles[type] || 'Books';
    
    // Show loading state
    modalContent.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p class="text-gray-500 mt-2">Loading books...</p>
        </div>
    `;
    
    // Show modal with blur and scroll prevention
    modal.classList.remove('hidden');
    document.body.classList.add('modal-open');
    
    // Load books data
    loadBooksForModal(type);
}

function closeBooksModal() {
    const modal = document.getElementById('books-modal');
    modal.classList.add('hidden');
    document.body.classList.remove('modal-open');
}

function loadBooksForModal(type) {
    const userId = {{ $user->id }};
    const modalContent = document.getElementById('modal-content');
    
    // Map type to endpoint
    const endpoints = {
        'want_to_read': `/users/${userId}/want-to-read`,
        'currently_reading': `/users/${userId}/currently-reading`,
        'finished_reading': `/users/${userId}/finished-reading`,
        'wishlist': `/users/${userId}/wishlist`
    };
    
    const endpoint = endpoints[type];
    if (!endpoint) {
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-500">Error: Invalid book type</p>
            </div>
        `;
        return;
    }
    
    // Make AJAX request
    fetch(endpoint, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(books => {
        console.log('Books loaded:', books); // Debug log
        displayBooksInModal(books);
    })
    .catch(error => {
        console.error('Error loading books:', error);
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                </svg>
                <h3 class="text-lg font-medium text-red-900 mb-2">Error Loading Books</h3>
                <p class="text-red-600 mb-2">Failed to load books: ${error.message}</p>
                <button onclick="loadBooksForModal('${type}')" 
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Try Again
                </button>
            </div>
        `;
    });
}

function displayBooksInModal(books) {
    const modalContent = document.getElementById('modal-content');
    
    if (!books || books.length === 0) {
        modalContent.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No books found</h3>
                <p class="text-gray-500 mb-4">No books in this category yet.</p>
                <a href="/books" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Browse Books
                </a>
            </div>
        `;
        return;
    }
    
    // Generate books HTML
    let booksHtml = '<div class="grid gap-4">';
    
    books.forEach(book => {
        const authorsText = book.authors ? book.authors.map(author => `${author.first_name} ${author.last_name}`).join(', ') : 'Unknown Author';
        const genresText = book.genres ? book.genres.map(genre => genre.name).join(', ') : '';
        const coverImage = book.cover_image || '';
        const publicationYear = book.publication_date ? new Date(book.publication_date).getFullYear() : '';
        
        booksHtml += `
            <div class="flex gap-4 p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex-shrink-0">
                    <a href="/books/${book.id}" class="block w-16 h-20 bg-gray-100 rounded-lg overflow-hidden hover:opacity-80 transition-opacity cursor-pointer">
                        ${coverImage ? 
                            `<img src="${coverImage}" alt="${book.title}" class="w-full h-full object-cover">` :
                            `<div class="w-full h-full bg-gradient-to-br from-green-400 to-blue-600 flex items-center justify-center">
                                <span class="text-white text-xs font-bold text-center px-1">${book.title.substring(0, 15)}</span>
                            </div>`
                        }
                    </a>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-gray-900 mb-1">
                        <a href="/books/${book.id}" class="hover:text-blue-600 transition-colors">
                            ${book.title}
                        </a>
                    </h4>
                    <p class="text-sm text-gray-600 mb-1">by ${authorsText}</p>
                    ${publicationYear ? `<p class="text-xs text-gray-500 mb-1">${publicationYear}</p>` : ''}
                    ${genresText ? `<p class="text-xs text-gray-500">${genresText}</p>` : ''}
                    ${book.description ? `<p class="text-xs text-gray-600 mt-2 line-clamp-2">${book.description.substring(0, 100)}${book.description.length > 100 ? '...' : ''}</p>` : ''}
                </div>
            </div>
        `;
    });
    
    booksHtml += '</div>';
    modalContent.innerHTML = booksHtml;
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('books-modal');
    if (event.target === modal) {
        closeBooksModal();
    }
});

// Edit Profile Modal Functions
function openEditProfileModal() {
    const modal = document.getElementById('edit-profile-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Add fade-in animation
    requestAnimationFrame(() => {
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.95)';
        modal.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        
        requestAnimationFrame(() => {
            modal.style.opacity = '1';
            modal.style.transform = 'scale(1)';
        });
    });
}

function closeEditProfileModal() {
    const modal = document.getElementById('edit-profile-modal');
    
    // Add fade-out animation
    modal.style.opacity = '0';
    modal.style.transform = 'scale(0.95)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        modal.style.transition = '';
    }, 300);
}

    function submitEditProfile() {
        const form = document.getElementById('edit-profile-form');
        const submitButton = event.target;
        
        // Get form fields
        const firstName = document.getElementById('modal_first_name').value.trim();
        const lastName = document.getElementById('modal_last_name').value.trim();
        const email = document.getElementById('modal_email').value.trim();
        
        // Clear previous error messages
        clearValidationErrors();
        
        // Validate required fields
        let hasErrors = false;
        
        if (!firstName) {
            showFieldError('modal_first_name', 'First name is required');
            hasErrors = true;
        }
        
        if (!lastName) {
            showFieldError('modal_last_name', 'Last name is required');
            hasErrors = true;
        }
        
        if (!email) {
            showFieldError('modal_email', 'Email address is required');
            hasErrors = true;
        } else if (!isValidEmail(email)) {
            showFieldError('modal_email', 'Please enter a valid email address');
            hasErrors = true;
        }
        
        // If validation fails, don't submit
        if (hasErrors) {
            // Show error toast
            showToast('Please fill in all required fields', 'error');
            return;
        }
        
        // Disable submit button and show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Saving...
        `;
        
        // Submit the form
        form.submit();
    }

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const fieldContainer = field.parentElement;
        
        // Add error styling
        field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        field.classList.remove('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
        
        // Create error message element
        const errorElement = document.createElement('p');
        errorElement.className = 'text-red-600 text-sm mt-1 validation-error';
        errorElement.textContent = message;
        
        // Append error message
        fieldContainer.appendChild(errorElement);
    }

    function clearValidationErrors() {
        // Remove error styling from all fields
        const fields = ['modal_first_name', 'modal_last_name', 'modal_email'];
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            field.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
        });
        
        // Remove all error messages
        const errorMessages = document.querySelectorAll('.validation-error');
        errorMessages.forEach(error => error.remove());
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validateField(fieldId, fieldName) {
        const field = document.getElementById(fieldId);
        const value = field.value.trim();
        
        if (!value) {
            showFieldError(fieldId, `${fieldName} is required`);
            return false;
        }
        
        // Special validation for email
        if (fieldId === 'modal_email' && !isValidEmail(value)) {
            showFieldError(fieldId, 'Please enter a valid email address');
            return false;
        }
        
        return true;
    }

    function clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        const fieldContainer = field.parentElement;
        
        // Remove error styling
        field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        field.classList.add('border-gray-300', 'focus:ring-blue-500', 'focus:border-blue-500');
        
        // Remove error message
        const existingError = fieldContainer.querySelector('.validation-error');
        if (existingError) {
            existingError.remove();
        }
    }

    // Profile image preview and form validation
    document.addEventListener('DOMContentLoaded', function() {
        const profileImageInput = document.getElementById('modal_profile_image');
        if (profileImageInput) {
            profileImageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('modal-profile-preview');
                        const initials = document.getElementById('modal-profile-initials');
                        
                        if (preview) {
                            preview.src = e.target.result;
                        } else if (initials) {
                            // Replace initials with image
                            const container = initials.parentElement;
                            container.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="w-full h-full object-cover" id="modal-profile-preview">`;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Add real-time validation for required fields
        const requiredFields = [
            { id: 'modal_first_name', name: 'First name' },
            { id: 'modal_last_name', name: 'Last name' },
            { id: 'modal_email', name: 'Email address' }
        ];

        requiredFields.forEach(fieldInfo => {
            const field = document.getElementById(fieldInfo.id);
            if (field) {
                // Clear errors when user starts typing
                field.addEventListener('input', function() {
                    clearFieldError(fieldInfo.id);
                });

                // Validate on blur
                field.addEventListener('blur', function() {
                    validateField(fieldInfo.id, fieldInfo.name);
                });
            }
        });

        // Close edit profile modal when clicking backdrop
        const editModal = document.getElementById('edit-profile-modal');
        if (editModal) {
            editModal.addEventListener('click', function(event) {
                if (event.target === this) {
                    closeEditProfileModal();
                }
            });
        }
    });

    // Enhanced ESC key handling for multiple modals
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const editModal = document.getElementById('edit-profile-modal');
            const booksModal = document.getElementById('books-modal');
            
            if (editModal && !editModal.classList.contains('hidden')) {
                closeEditProfileModal();
            } else if (booksModal && !booksModal.classList.contains('hidden')) {
                closeBooksModal();
            }
        }
    });
</script>

@endsection