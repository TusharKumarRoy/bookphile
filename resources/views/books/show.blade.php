@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="book-id" content="{{ $book->id }}">

<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('books.index') }}" class="hover:text-blue-600">Books</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900">{{ $book->title }}</li>
            </ol>
        </nav>
        
        <!-- Main Book Section -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
            <div class="md:flex">
                <!-- Book Cover and Actions -->
                <div class="md:w-1/3 lg:w-1/4 p-6">
                    <!-- Book Cover -->
                    <div class="aspect-[3/4] bg-gray-100 relative mb-6 rounded-lg overflow-hidden">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                                <span class="text-white text-lg font-bold text-center px-4">{{ $book->title }}</span>
                            </div>
                        @endif
                    </div>
                    
                    @auth
                        <!-- Reading Status Dropdown -->
                        <div class="mb-4 relative dropdown-container">
                            <button id="reading-status-btn" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors {{ isset($userInteractions['reading_status']) && $userInteractions['reading_status'] ? 'bg-green-50 border-green-300 text-green-700' : '' }}">
                                <svg class="w-4 h-4 mr-2 {{ isset($userInteractions['reading_status']) && $userInteractions['reading_status'] ? 'text-green-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                <span id="reading-status-text">
                                    @if(isset($userInteractions['reading_status']) && $userInteractions['reading_status'])
                                        {{ ucwords(str_replace('_', ' ', $userInteractions['reading_status']->status)) }}
                                    @else
                                        Want To Read
                                    @endif
                                </span>
                                <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Hidden dropdown menu -->
                            <div id="reading-status-dropdown" class="dropdown-menu hidden absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg">
                                <button data-status="want_to_read" class="w-full px-4 py-3 text-left hover:bg-gray-50 rounded-t-lg">Want To Read</button>
                                <button data-status="currently_reading" class="w-full px-4 py-3 text-left hover:bg-gray-50">Currently Reading</button>
                                <button data-status="finished_reading" class="w-full px-4 py-3 text-left hover:bg-gray-50">Finished Reading</button>
                                @if(isset($userInteractions['reading_status']) && $userInteractions['reading_status'])
                                    <hr class="border-gray-200 my-1">
                                    <button data-status="remove" class="w-full px-4 py-3 text-left text-red-600 hover:bg-red-50 rounded-b-lg">Remove Status</button>
                                @endif
                                <!-- JavaScript will also manage this dynamically -->
                            </div>
                        </div>
                        
                        <!-- Wishlist Button -->
                        <button id="wishlist-btn" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors mb-4 {{ isset($userInteractions['is_in_wishlist']) && $userInteractions['is_in_wishlist'] ? 'bg-red-50 border-red-300 text-red-700' : '' }}">
                            <svg id="wishlist-icon" class="w-5 h-5 mr-2 {{ isset($userInteractions['is_in_wishlist']) && $userInteractions['is_in_wishlist'] ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ isset($userInteractions['is_in_wishlist']) && $userInteractions['is_in_wishlist'] ? 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z' : 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' }}"></path>
                            </svg>
                            <span id="wishlist-text">
                                {{ isset($userInteractions['is_in_wishlist']) && $userInteractions['is_in_wishlist'] ? 'In Wishlist' : 'Add to Wishlist' }}
                            </span>
                        </button>
                        
                        <!-- Rate This Book -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Rate this book</p>
                            <div class="flex justify-center space-x-1 mb-4" id="user-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <button class="star-btn text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="w-full block text-center px-4 py-2 border border-black text-gray-700 rounded-lg hover:bg-black hover:text-white transition-colors">
                            Login to Track This Book
                        </a>
                        <!-- text-sm lg:text-base text-gray-700 hover:text-blue-600 border border-black rounded px-3 py-1 hover:bg-black hover:text-white transition -->
                        
                    @endauth
                    
                    @guest
                        <!-- Guest Users Message -->
                        <div class="text-center p-6 bg-gray-100 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Join Bookphile to interact with books</h3>
                            <p class="text-gray-600 mb-4">Rate books, write reviews, and manage your reading list</p>
                            <div class="space-x-3">
                                <a href="{{ route('login') }}" class="px-4 py-2 border border-black text-gray-700 rounded-lg hover:bg-black hover:text-white transition-colors">Sign In</a>
                                <a href="{{ route('register') }}" class="px-4 py-2 border border-black text-gray-700 rounded-lg hover:bg-black hover:text-white transition-colors">Sign Up</a>
                            </div>
                        </div>
                    @endguest
                </div>
                
                <!-- Book Details -->
                <div class="md:w-2/3 lg:w-3/4 p-6">
                    <!-- Title and Author -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $book->title }}</h1>
                        <p class="text-xl text-gray-600 mb-4">
                            by 
                            @foreach($book->authors as $index => $author)
                                <a href="{{ route('authors.show', $author) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $author->getFullNameAttribute() }}
                                </a>@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        
                        <!-- Rating Display -->
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($book->average_rating))
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-lg font-semibold">{{ number_format($book->average_rating, 2) }}</span>
                            <span class="text-gray-500">{{ number_format($book->ratings_count) }} ratings • {{ number_format($organizedReviews->count()) }} reviews</span>
                        </div>
                        
                        <!-- Subtitle/Tagline -->
                        @if($book->subtitle)
                            <p class="text-lg text-gray-700 mb-4">{{ $book->subtitle }}</p>
                        @endif
                    </div>
                    
                    <!-- Book Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 p-4 bg-gray-50 rounded-lg">
                        @if($book->publication_year)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Published</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ $book->publication_year }}</dd>
                            </div>
                        @endif
                        
                        @if($book->page_count)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Pages</dt>
                                <dd class="text-lg font-semibold text-gray-900">{{ number_format($book->page_count) }}</dd>
                            </div>
                        @endif
                        
                        @if($book->isbn)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                                <dd class="text-sm font-mono text-gray-900">{{ $book->isbn }}</dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Genres</dt>
                            <dd class="flex flex-wrap gap-1 mt-1">
                                @foreach($book->genres as $genre)
                                    <a href="{{ route('genres.show', $genre) }}" class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full hover:bg-blue-200 transition-colors">
                                        {{ $genre->name }}
                                    </a>
                                @endforeach
                            </dd>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    @if($book->description)
                        <div class="mb-8">
                            @php
                                $description = $book->description;
                                $shortDescription = Str::limit($description, 300);
                                $needsShowMore = strlen($description) > 300;
                            @endphp
                            
                            <div id="description-container">
                                <p class="text-gray-700 leading-relaxed" id="description-text">{{ $shortDescription }}</p>
                                @if($needsShowMore)
                                    <button id="show-more-btn" class="text-blue-600 hover:text-blue-800 text-sm mt-2 transition-colors" onclick="toggleDescription()">
                                        Show more
                                    </button>
                                @endif
                            </div>
                            
                            @if($needsShowMore)
                                <script>
                                    function toggleDescription() {
                                        const descriptionText = document.getElementById('description-text');
                                        const showMoreBtn = document.getElementById('show-more-btn');
                                        const fullDescription = {!! json_encode($description) !!};
                                        const shortDescription = {!! json_encode($shortDescription) !!};
                                        
                                        if (showMoreBtn.textContent.trim() === 'Show more') {
                                            descriptionText.textContent = fullDescription;
                                            showMoreBtn.textContent = 'Show less';
                                        } else {
                                            descriptionText.textContent = shortDescription;
                                            showMoreBtn.textContent = 'Show more';
                                        }
                                    }
                                </script>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- About the Author -->
        @if($book->authors->count() > 0)
            @foreach($book->authors as $author)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">About the author</h2>
                        <div class="flex gap-6">
                            <!-- Author Photo -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('authors.show', $author) }}" class="block hover:opacity-80 transition-opacity">
                                    <img class="w-20 h-20 rounded-full object-cover hover:shadow-lg transition-shadow cursor-pointer" 
                                         src="{{ $author->image_url }}" 
                                         alt="{{ $author->getFullNameAttribute() }}" 
                                         loading="lazy" 
                                         decoding="async"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ substr($author->first_name, 0, 1) }}{{ substr($author->last_name, 0, 1) }}&color=ffffff&background=10b981&size=512'">
                                </a>
                            </div>
                            
                            <!-- Author Info -->
                            <div class="flex-1">
                                <div class="mb-4">
                                    <a href="{{ route('authors.show', $author) }}" class="hover:text-blue-600 transition-colors">
                                        <h3 class="text-lg font-semibold text-gray-900 hover:text-blue-600">{{ $author->getFullNameAttribute() }}</h3>
                                    </a>
                                    <p class="text-sm text-gray-600">{{ $author->books->count() }} {{ Str::plural('book', $author->books->count()) }}</p>
                                </div>
                                
                                @if($author->biography)
                                    <p class="text-gray-700 leading-relaxed">
                                        {{ Str::limit($author->biography, 400) }}
                                        @if(strlen($author->biography) > 400)
                                            <button class="text-blue-600 hover:text-blue-800">...more</button>
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        
        <!-- Ratings & Reviews -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Ratings & Reviews</h2>
                
                @auth
                    <!-- User Rating Section -->
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
                        </div>
                        <h3 class="text-lg font-semibold text-center mb-4">What do <em>you</em> think?</h3>
                        
                        <!-- User Rating Stars -->
                        <div class="flex justify-center space-x-1 mb-4" id="user-rating-large">
                            @for($i = 1; $i <= 5; $i++)
                                <button class="star-btn-large {{ isset($userInteractions['rating']) && $userInteractions['rating'] >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                                    <svg class="w-8 h-8 fill-current" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        
                        <div class="text-center">
                            <span class="text-sm text-gray-600">
                                @if(isset($userInteractions['rating']) && $userInteractions['rating'])
                                    You rated this book {{ $userInteractions['rating'] }} star{{ $userInteractions['rating'] > 1 ? 's' : '' }}
                                @else
                                    Rate this book
                                @endif
                            </span>
                        </div>
                        
                        <!-- Write/Edit Review Button -->
                        <div class="text-center mt-4">
                            <button id="write-review-btn" class="px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                                @if(isset($userInteractions['review']) && $userInteractions['review'])
                                    Edit Review
                                @else
                                    Write a Review
                                @endif
                            </button>
                            
                            <!-- Rating and Review Management -->
                            <div class="flex justify-center gap-3 mt-3">
                                @if(isset($userInteractions['rating']) && $userInteractions['rating'])
                                    <button id="clear-rating-btn" class="text-sm text-red-600 hover:text-red-800 underline">
                                        Clear Rating
                                    </button>
                                @endif
                                
                                @if(isset($userInteractions['review']) && $userInteractions['review'])
                                    <button id="delete-review-btn" class="text-sm text-red-600 hover:text-red-800 underline">
                                        Delete Review
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Review Form (Hidden by default) -->
                        <div id="review-form" class="hidden mt-6">
                            <textarea class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                      rows="6" 
                                      placeholder="What did you think of this book? Share your thoughts...">@if(isset($userInteractions['review']) && $userInteractions['review']){{ $userInteractions['review']->review_text }}@endif</textarea>
                            <div class="flex justify-between items-center mt-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="spoiler-checkbox" class="mr-2" @if(isset($userInteractions['review']) && $userInteractions['review'] && $userInteractions['review']->is_spoiler) checked @endif>
                                    <span class="text-sm text-gray-600">Contains spoilers</span>
                                </label>
                                <div class="flex gap-3">
                                    <button id="cancel-review-btn" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                                    <button id="submit-review-btn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        @if(isset($userInteractions['review']) && $userInteractions['review'])
                                            Update Review
                                        @else
                                            Post Review
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth

                <!-- All Ratings & Reviews -->
                <div class="space-y-6">
                    @if($organizedReviews->count() > 0 || $allRatings->count() > 0)
                        <div class="border-b border-gray-200 pb-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Community Ratings & Reviews</h3>
                            <p class="text-sm text-gray-600">
                                {{ $allRatings->count() }} rating{{ $allRatings->count() != 1 ? 's' : '' }}
                                @if($organizedReviews->count() > 0)
                                    • {{ $organizedReviews->count() }} review{{ $organizedReviews->count() != 1 ? 's' : '' }}
                                @endif
                            </p>
                        </div>

                        <!-- Reviews with Ratings -->
                        @foreach($organizedReviews as $review)
                            <div class="border-b border-gray-200 pb-6 mb-6 last:border-b-0">
                                <div class="flex gap-4">
                                    <!-- User Avatar (Clickable) -->
                                    <a href="{{ route('users.show', $review->user) }}" class="user-avatar w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 hover:opacity-80 transition-opacity cursor-pointer overflow-hidden" title="View {{ $review->user->full_name }}'s profile">
                                        @if($review->user->profile_image)
                                            <img src="{{ asset('storage/' . $review->user->profile_image) }}" alt="{{ $review->user->full_name }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                <span class="text-white font-semibold text-lg">
                                                    {{ strtoupper(substr($review->user->full_name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </a>
                                    
                                    <div class="flex-1">
                                        <!-- User Full Name -->
                                        <div class="mb-2">
                                            <h4 class="font-semibold text-gray-900 text-lg">
                                                <a href="{{ route('users.show', $review->user) }}" class="hover:text-blue-600 transition-colors">
                                                    {{ $review->user->full_name }}
                                                </a>
                                                @if($review->user_id === auth()->id())
                                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full ml-2">Your Review</span>
                                                @endif
                                            </h4>
                                        </div>
                                        
                                        <!-- Date -->
                                        <p class="text-sm text-gray-500 mb-3">{{ $review->created_at->format('M j, Y') }}</p>
                                        
                                        <!-- User's Rating for this review -->
                                        @php
                                            $userRatingForReview = $allRatings->where('user_id', $review->user_id)->first();
                                        @endphp
                                        @if($userRatingForReview)
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $userRatingForReview->rating)
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
                                                <span class="text-sm text-gray-600">{{ $userRatingForReview->rating }}/5 stars</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Review Text -->
                                        <div class="text-gray-700 leading-relaxed">
                                            {!! nl2br(e($review->review_text)) !!}
                                        </div>
                                        
                                        @if($review->is_spoiler)
                                            <div class="mt-2">
                                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Contains Spoilers</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Ratings Only (users who rated but didn't review) -->
                        @php
                            $ratingsOnly = $allRatings->whereNotIn('user_id', $organizedReviews->pluck('user_id'));
                        @endphp
                        
                        @if($ratingsOnly->count() > 0)
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-4">Other Ratings</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($ratingsOnly as $rating)
                                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                            <!-- User Avatar (Clickable) -->
                                            <a href="{{ route('users.show', $rating->user) }}" class="user-avatar w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 hover:opacity-80 transition-opacity cursor-pointer overflow-hidden" title="View {{ $rating->user->full_name }}'s profile">
                                                @if($rating->user->profile_image)
                                                    <img src="{{ asset('storage/' . $rating->user->profile_image) }}" alt="{{ $rating->user->full_name }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center">
                                                        <span class="text-white font-semibold text-sm">
                                                            {{ strtoupper(substr($rating->user->full_name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </a>
                                            
                                            <div class="flex-1">
                                                <!-- User Full Name -->
                                                <p class="font-medium text-gray-900 text-base mb-1">
                                                    <a href="{{ route('users.show', $rating->user) }}" class="hover:text-blue-600 transition-colors">
                                                        {{ $rating->user->full_name }}
                                                    </a>
                                                </p>
                                                <!-- Date -->
                                                <p class="text-xs text-gray-500 mb-2">{{ $rating->created_at->format('M j, Y') }}</p>
                                                <!-- Rating -->
                                                <div class="flex items-center gap-2">
                                                    <div class="flex text-yellow-400">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $rating->rating)
                                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                                </svg>
                                                            @else
                                                                <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="text-xs text-gray-500">{{ $rating->created_at->format('M j, Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h3>
                            <p class="text-gray-600">Be the first to rate and review this book!</p>
                        </div>
                    @endif
                </div>
                </div>
            </div>
        </div>
        
        <!-- Related Books -->
        @if($relatedBooks->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center underline underline-offset-5 decoration-3">Related Books</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($relatedBooks as $relatedBook)
                        <a href="{{ route('books.show', $relatedBook) }}" class="group">
                            <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                                <div class="aspect-[3/4] bg-gray-100 relative overflow-hidden">
                                    @if($relatedBook->cover_image)
                                        <img src="{{ $relatedBook->cover_image_url }}" alt="{{ $relatedBook->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" loading="lazy" decoding="async">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                                            <span class="text-white text-xs font-bold text-center px-2">{{ $relatedBook->title }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3">
                                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1">{{ $relatedBook->title }}</h3>
                                    <p class="text-gray-600 text-xs">{{ number_format($relatedBook->average_rating, 1) }} ⭐</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Performance optimizations */
    .star-btn, .star-btn-large {
        will-change: transform, opacity;
        transform: translateZ(0); /* Force GPU acceleration */
        backface-visibility: hidden;
    }
    
    /* Optimized star states with GPU acceleration */
    .star-filled {
        color: #fbbf24 !important;
        transform: translateZ(0);
    }
    
    .star-empty {
        color: #d1d5db !important;
        transform: translateZ(0);
    }
    
    /* Optimized star loading state */
    .star-loading {
        opacity: 0.5 !important;
        transform: scale(0.95) translateZ(0);
        transition: all 0.15s ease-out;
    }
    
    /* Optimized star hover effects */
    .star-btn:hover, .star-btn-large:hover {
        transform: scale(1.05) translateZ(0);
        transition: all 0.15s ease-out;
    }
    
    /* User avatar styles with GPU acceleration */
    .user-avatar {
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease-out;
        transform: translateZ(0);
        backface-visibility: hidden;
    }
    
    .user-avatar:hover {
        border-color: #3b82f6;
        transform: scale(1.05) translateZ(0);
    }
    
    /* Optimized image loading */
    img {
        content-visibility: auto;
        contain-intrinsic-size: 300px 400px;
    }
    
    /* Dropdown styling with GPU acceleration */
    .dropdown-container {
        position: relative;
        z-index: 50;
        transform: translateZ(0);
    }
    
    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 9999 !important;
        min-width: 100%;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        margin-top: 0.25rem;
        overflow: visible;
        transform: translateZ(0);
        backface-visibility: hidden;
    }
    
    .dropdown-menu button {
        display: block !important;
        width: 100% !important;
        text-align: left !important;
        padding: 0.75rem 1rem !important;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: background-color 0.1s ease-out;
        transform: translateZ(0);
    }
    
    .dropdown-menu button:hover {
        background-color: #f9fafb !important;
    }
    
    .dropdown-menu button:first-child {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    
    .dropdown-menu button:last-child {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }
    
    .dropdown-menu button[data-status="remove"] {
        color: #dc2626 !important;
    }
    
    .dropdown-menu button[data-status="remove"]:hover {
        background-color: #fef2f2 !important;
    }
    
    /* Ensure parent containers don't clip the dropdown */
    .md\\:w-1\\/3, .lg\\:w-1\\/4 {
        overflow: visible !important;
    }
    
    .bg-white.rounded-lg.shadow-sm {
        overflow: visible !important;
    }
    
    /* Performance optimizations for frequent animations */
    .transition-colors, .transition-transform, .transition-opacity {
        will-change: auto;
    }
    
    /* Optimize hover animations for better performance */
    .group-hover\\:scale-105 {
        will-change: transform;
        transform: translateZ(0);
    }
    
    /* Toast animations optimization */
    .toast-animation {
        will-change: transform, opacity;
        transform: translateZ(0);
        backface-visibility: hidden;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @auth
    // Setup CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const bookId = document.querySelector('meta[name="book-id"]').getAttribute('content');
    
    console.log('Initializing with Book ID:', bookId);
    console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');
    
    // Test authentication first
    fetch(`/api/books/${bookId}/test-auth`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Authentication test passed:', data.message);
        } else {
            console.error('❌ Authentication test failed:', data);
        }
    })
    .catch(error => {
        console.error('❌ Authentication test error:', error);
    });
    
    // Helper function for AJAX requests
    function makeAjaxRequest(url, method = 'GET', data = null) {
        console.log(`Making ${method} request to: ${url}`);
        
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        };
        
        if (data) {
            options.body = JSON.stringify(data);
            console.log('Request data:', data);
        }
        
        return fetch(url, options)
            .then(response => {
                console.log(`Response status: ${response.status}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(responseData => {
                console.log('Response data:', responseData);
                return responseData;
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                alert('Error: ' + error.message);
                return { success: false, message: error.message };
            });
    }
    
    // Reading Status Dropdown
    const readingStatusBtn = document.getElementById('reading-status-btn');
    const readingStatusDropdown = document.getElementById('reading-status-dropdown');
    const readingStatusText = document.getElementById('reading-status-text');
    
    if (readingStatusBtn && readingStatusDropdown) {
        // Toggle dropdown
        readingStatusBtn.addEventListener('click', function(e) {
            e.preventDefault();
            readingStatusDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!readingStatusBtn.contains(e.target) && !readingStatusDropdown.contains(e.target)) {
                readingStatusDropdown.classList.add('hidden');
            }
        });

        // Handle status selection
        const statusOptions = readingStatusDropdown.querySelectorAll('button[data-status]');
        statusOptions.forEach(option => {
            option.addEventListener('click', function() {
                const status = this.getAttribute('data-status');
                const statusText = this.textContent;
                
                readingStatusDropdown.classList.add('hidden');
                
                if (status === 'remove') {
                    // Handle remove status
                    makeAjaxRequest(`/api/books/${bookId}/reading-status`, 'DELETE')
                    .then(response => {
                        if (response.success) {
                            // Reset to default state
                            readingStatusText.textContent = 'Want To Read';
                            
                            // Remove selected styling from button
                            readingStatusBtn.classList.remove('bg-green-50', 'border-green-300', 'text-green-700');
                            readingStatusBtn.classList.add('border-gray-300', 'text-gray-700');
                            
                            // Reset icon color
                            const icon = readingStatusBtn.querySelector('svg');
                            if (icon) {
                                icon.classList.remove('text-green-600');
                                icon.classList.add('text-gray-500');
                            }
                            
                            // Remove the "Remove Status" option and separator
                            updateRemoveStatusOption(false);
                            
                            // Show success notification
                            showToast('Reading status removed successfully', 'remove');
                            console.log('Reading status removed successfully');
                        }
                    })
                    .catch(error => {
                        showToast('Failed to remove reading status', 'error');
                    });
                    return;
                }
                
                // Set new status
                readingStatusText.textContent = statusText;
                
                // Add selected styling to button
                readingStatusBtn.classList.add('bg-green-50', 'border-green-300', 'text-green-700');
                readingStatusBtn.classList.remove('border-gray-300', 'text-gray-700');
                
                // Update icon color
                const icon = readingStatusBtn.querySelector('svg');
                if (icon) {
                    icon.classList.add('text-green-600');
                    icon.classList.remove('text-gray-500');
                }
                
                // Show the remove option since there's now a status
                updateRemoveStatusOption(true);
                
                // Send status update to server
                makeAjaxRequest(`/api/books/${bookId}/reading-status`, 'PUT', { status: status })
                .then(response => {
                    if (response.success) {
                        // Show success notification
                        const statusDisplayText = statusText.toLowerCase();
                        showToast(`Book marked as "${statusDisplayText}"`);
                        console.log('Reading status updated successfully');
                    }
                })
                .catch(error => {
                    showToast('Failed to update reading status', 'error');
                });
            });
        });
    }
    
    // Wishlist Button
    const wishlistBtn = document.getElementById('wishlist-btn');
    const wishlistIcon = document.getElementById('wishlist-icon');
    const wishlistText = document.getElementById('wishlist-text');
    
    if (wishlistBtn && wishlistIcon && wishlistText) {
        wishlistBtn.addEventListener('click', function() {
            makeAjaxRequest(`/api/books/${bookId}/wishlist/toggle`, 'POST')
                .then(response => {
                    if (response.success) {
                        const isInWishlist = response.data.is_in_wishlist;
                        const path = wishlistIcon.querySelector('path');
                        
                        if (isInWishlist) {
                            // Add to wishlist - filled heart
                            path.setAttribute('d', 'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z');
                            wishlistIcon.classList.add('text-red-500');
                            wishlistIcon.classList.remove('text-gray-400');
                            wishlistText.textContent = 'In Wishlist';
                            this.classList.add('bg-red-50', 'border-red-300', 'text-red-700');
                            this.classList.remove('border-gray-300', 'text-gray-700');
                            
                            // Show success notification
                            showToast('Book added to wishlist', 'add-wishlist');
                        } else {
                            // Remove from wishlist - empty heart
                            path.setAttribute('d', 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z');
                            wishlistIcon.classList.remove('text-red-500');
                            wishlistIcon.classList.add('text-gray-400');
                            wishlistText.textContent = 'Add to Wishlist';
                            this.classList.remove('bg-red-50', 'border-red-300', 'text-red-700');
                            this.classList.add('border-gray-300', 'text-gray-700');
                            
                            // Show success notification
                            showToast('Book removed from wishlist', 'remove');
                        }
                    }
                })
                .catch(error => {
                    showToast('Failed to update wishlist', 'error');
                });
        });
    }
    
    // Star Rating System - Optimized with debouncing and performance improvements
    const starButtons = document.querySelectorAll('.star-btn');
    const starButtonsLarge = document.querySelectorAll('.star-btn-large');
    let currentUserRating = {{ isset($userInteractions['rating']) ? $userInteractions['rating'] : 0 }};
    let isRatingInProgress = false; // Prevent rapid multiple clicks
    
    // Debounce function for performance optimization
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Throttle function for smooth animations
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }
    
    // Helper function to sync star ratings between small and large sections
    function syncStarRatings(rating) {
        // Use requestAnimationFrame for smooth DOM updates
        requestAnimationFrame(() => {
            highlightStars(starButtons, rating);
            highlightStars(starButtonsLarge, rating);
        });
    }
    
    // Helper function to update rating text and clear rating button visibility
    function updateRatingDisplay(rating) {
        const ratingText = document.querySelector('#user-rating-large').nextElementSibling.querySelector('span');
        const clearRatingBtn = document.getElementById('clear-rating-btn');
        
        if (ratingText) {
            if (rating > 0) {
                ratingText.textContent = `You rated this book ${rating} star${rating > 1 ? 's' : ''}`;
            } else {
                ratingText.textContent = 'Rate this book';
            }
        }
        
        if (clearRatingBtn && clearRatingBtn.parentElement) {
            clearRatingBtn.style.display = rating > 0 ? 'inline' : 'none';
        }
    }
    
    // Optimized star highlighting with batch DOM updates
    function highlightStars(stars, rating) {
        // Batch DOM updates for better performance
        const updates = [];
        stars.forEach((star, index) => {
            if (index < rating) {
                updates.push(() => {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                });
            } else {
                updates.push(() => {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                });
            }
        });
        
        // Execute all updates in a single frame
        requestAnimationFrame(() => {
            updates.forEach(update => update());
        });
    }
    
    // Optimized star rating handler with performance improvements
    function handleStarRating(stars, isLarge = false) {
        // Debounced hover handler for better performance
        const debouncedHover = debounce((rating, starsGroup) => {
            highlightStars(starsGroup, rating);
        }, 16); // ~60fps
        
        // Throttled mouse leave handler
        const throttledMouseLeave = throttle(() => {
            syncStarRatings(currentUserRating);
        }, 16); // ~60fps
        
        stars.forEach((star, index) => {
            // Optimized hover handling
            star.addEventListener('mouseenter', function() {
                if (isRatingInProgress) return; // Prevent hover during rating submission
                const rating = parseInt(this.getAttribute('data-rating'));
                debouncedHover(rating, stars);
            });
            
            star.addEventListener('mouseleave', function() {
                if (isRatingInProgress) return;
                throttledMouseLeave();
            });
            
            // Optimized click handling with request locking
            star.addEventListener('click', function() {
                if (isRatingInProgress) return; // Prevent multiple rapid clicks
                
                const rating = parseInt(this.getAttribute('data-rating'));
                isRatingInProgress = true;
                
                // Immediate visual feedback with GPU acceleration
                this.style.transform = 'scale(0.95)';
                this.style.opacity = '0.7';
                this.style.transition = 'all 0.1s ease-out';
                
                // If user clicks the same rating they already have, clear it
                if (currentUserRating === rating) {
                    makeAjaxRequest(`/api/books/${bookId}/rating`, 'DELETE')
                        .then(response => {
                            if (response.success) {
                                // Store notification for after page refresh
                                localStorage.setItem('bookphile_notification', JSON.stringify({
                                    message: 'Rating cleared successfully!',
                                    type: 'clear'
                                }));
                                
                                console.log('Rating cleared successfully');
                                // Immediately refresh the page for fast update
                                window.location.reload();
                            } else {
                                // Restore button if failed
                                this.style.transform = '';
                                this.style.opacity = '';
                                isRatingInProgress = false;
                            }
                        })
                        .catch(error => {
                            // Restore button if failed
                            this.style.transform = '';
                            this.style.opacity = '';
                            isRatingInProgress = false;
                        });
                } else {
                    // Set new rating
                    makeAjaxRequest(`/api/books/${bookId}/rating`, 'POST', { rating: rating })
                        .then(response => {
                            if (response.success) {
                                // Store notification for after page refresh
                                localStorage.setItem('bookphile_notification', JSON.stringify({
                                    message: `Book rated ${rating} star${rating > 1 ? 's' : ''}!`,
                                    type: 'success'
                                }));
                                
                                console.log('Rating saved successfully');
                                // Immediately refresh the page for fast update
                                window.location.reload();
                            } else {
                                // Restore button if failed
                                this.style.transform = '';
                                this.style.opacity = '';
                                isRatingInProgress = false;
                            }
                        })
                        .catch(error => {
                            // Restore button if failed
                            this.style.transform = '';
                            this.style.opacity = '';
                            isRatingInProgress = false;
                        });
                }
            });
        });
    }
    
    // Helper function to add or remove the "Remove Status" option
    function updateRemoveStatusOption(hasStatus) {
        const dropdown = document.getElementById('reading-status-dropdown');
        let removeOption = dropdown.querySelector('button[data-status="remove"]');
        let separator = dropdown.querySelector('hr');
        
        if (hasStatus && !removeOption) {
            // Add separator and remove option (only if they don't exist)
            separator = document.createElement('hr');
            separator.className = 'border-gray-200 my-1';
            dropdown.appendChild(separator);
            
            removeOption = document.createElement('button');
            removeOption.setAttribute('data-status', 'remove');
            removeOption.className = 'w-full px-4 py-3 text-left text-red-600 hover:bg-red-50 rounded-b-lg';
            removeOption.textContent = 'Remove Status';
            dropdown.appendChild(removeOption);
            
            // Add event listener to the new remove option
            removeOption.addEventListener('click', function() {
                const readingStatusDropdown = document.getElementById('reading-status-dropdown');
                const readingStatusText = document.getElementById('reading-status-text');
                const readingStatusBtn = document.getElementById('reading-status-btn');
                
                readingStatusDropdown.classList.add('hidden');
                
                // Remove the reading status
                makeAjaxRequest(`/api/books/${bookId}/reading-status`, 'DELETE')
                .then(response => {
                    if (response.success) {
                        // Reset to default state
                        readingStatusText.textContent = 'Want To Read';
                        
                        // Remove selected styling from button
                        readingStatusBtn.classList.remove('bg-green-50', 'border-green-300', 'text-green-700');
                        readingStatusBtn.classList.add('border-gray-300', 'text-gray-700');
                        
                        // Reset icon color
                        const icon = readingStatusBtn.querySelector('svg');
                        if (icon) {
                            icon.classList.remove('text-green-600');
                            icon.classList.add('text-gray-500');
                        }
                        
                        // Remove the "Remove Status" option and separator
                        updateRemoveStatusOption(false);
                        
                        // Show success notification
                        showToast('Reading status removed successfully', 'remove');
                        console.log('Reading status removed successfully');
                    }
                })
                .catch(error => {
                    showToast('Failed to remove reading status', 'error');
                });
            });
        } else if (!hasStatus && removeOption) {
            // Remove the option and separator
            if (separator) {
                separator.remove();
            }
            removeOption.remove();
        }
    }
    
    // Initialize star ratings
    if (starButtons.length > 0) {
        handleStarRating(starButtons);
    }
    
    if (starButtonsLarge.length > 0) {
        handleStarRating(starButtonsLarge, true);
    }
    
    // Sync initial star display and rating text
    syncStarRatings(currentUserRating);
    updateRatingDisplay(currentUserRating);
    
    // Initialize remove status option if user has a reading status
    const hasInitialReadingStatus = {{ isset($userInteractions['reading_status']) && $userInteractions['reading_status'] ? 'true' : 'false' }};
    if (readingStatusDropdown) {
        updateRemoveStatusOption(hasInitialReadingStatus);
    }
    
    // Write Review Form
    const writeReviewBtn = document.getElementById('write-review-btn');
    const reviewForm = document.getElementById('review-form');
    const clearRatingBtn = document.getElementById('clear-rating-btn');
    const deleteReviewBtn = document.getElementById('delete-review-btn');
    
    if (writeReviewBtn && reviewForm) {
        const textarea = reviewForm.querySelector('textarea');
        const spoilerCheckbox = document.getElementById('spoiler-checkbox');
        const cancelBtn = document.getElementById('cancel-review-btn');
        const submitBtn = document.getElementById('submit-review-btn');
        
        // Handle review form toggle
        writeReviewBtn.addEventListener('click', function() {
            reviewForm.classList.toggle('hidden');
            if (!reviewForm.classList.contains('hidden')) {
                textarea.focus();
            }
        });
        
        // Handle cancel button
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                reviewForm.classList.add('hidden');
                // Reset form if it was empty initially
                const hasExistingReview = {{ isset($userInteractions['review']) && $userInteractions['review'] ? 'true' : 'false' }};
                if (!hasExistingReview) {
                    textarea.value = '';
                    spoilerCheckbox.checked = false;
                }
            });
        }
        
        // Handle review submission
        if (submitBtn) {
            submitBtn.addEventListener('click', function() {
                const reviewText = textarea.value.trim();
                const isSpoiler = spoilerCheckbox.checked;
                
                if (reviewText.length < 10) {
                    alert('Review must be at least 10 characters long');
                    return;
                }
                
                // Determine if this is an update or new review
                const hasExistingReview = {{ isset($userInteractions['review']) && $userInteractions['review'] ? 'true' : 'false' }};
                const method = hasExistingReview ? 'PUT' : 'POST';
                const url = `/api/books/${bookId}/review`;
                
                // Show loading state
                const originalText = this.textContent;
                this.style.opacity = '0.5';
                this.textContent = hasExistingReview ? 'Updating...' : 'Posting...';
                this.disabled = true;
                
                makeAjaxRequest(url, method, { 
                    review_text: reviewText,
                    is_spoiler: isSpoiler
                })
                .then(response => {
                    if (response.success) {
                        // Store notification for after page refresh
                        const message = hasExistingReview ? 'Review updated successfully!' : 'Review posted successfully!';
                        localStorage.setItem('bookphile_notification', JSON.stringify({
                            message: message,
                            type: 'success'
                        }));
                        
                        console.log(message);
                        reviewForm.classList.add('hidden');
                        
                        // Update button text and show management options
                        writeReviewBtn.textContent = 'Edit Review';
                        
                        // Reload page to show updated review
                        window.location.reload();
                    } else {
                        // Restore button if failed
                        this.style.opacity = '1';
                        this.textContent = originalText;
                        this.disabled = false;
                        alert('Failed to save review. Please try again.');
                    }
                })
                .catch(error => {
                    // Restore button if failed
                    this.style.opacity = '1';
                    this.textContent = originalText;
                    this.disabled = false;
                    alert('Error saving review. Please try again.');
                });
            });
        }
    }
    
    // Clear Rating functionality
    if (clearRatingBtn) {
        clearRatingBtn.addEventListener('click', function() {
            // Show loading state
            this.style.opacity = '0.5';
            this.textContent = 'Clearing...';
                
            makeAjaxRequest(`/api/books/${bookId}/rating`, 'DELETE')
            .then(response => {
                if (response.success) {
                    // Store notification for after page refresh
                    localStorage.setItem('bookphile_notification', JSON.stringify({
                        message: 'Rating cleared successfully!',
                        type: 'clear'
                    }));
                    
                    console.log('Rating cleared successfully!');
                    // Immediately refresh the page for fast update
                    window.location.reload();
                } else {
                    // Restore button if failed
                    this.style.opacity = '1';
                    this.textContent = 'Clear Rating';
                    alert('Failed to clear rating. Please try again.');
                }
            })
            .catch(error => {
                // Restore button if failed
                this.style.opacity = '1';
                this.textContent = 'Clear Rating';
                alert('Error clearing rating. Please try again.');
            });
        });
    }
    
    // Delete Review functionality  
    if (deleteReviewBtn) {
        deleteReviewBtn.addEventListener('click', function() {
            // Show loading state
            this.style.opacity = '0.5';
            this.textContent = 'Deleting...';
                
            makeAjaxRequest(`/api/books/${bookId}/review`, 'DELETE')
            .then(response => {
                if (response.success) {
                    // Store notification for after page refresh
                    localStorage.setItem('bookphile_notification', JSON.stringify({
                        message: 'Review deleted successfully!',
                        type: 'delete'
                    }));
                    
                    console.log('Review deleted successfully!');
                    // Immediately refresh the page for fast update
                    window.location.reload();
                } else {
                    // Restore button if failed
                    this.style.opacity = '1';
                    this.textContent = 'Delete Review';
                    alert('Failed to delete review. Please try again.');
                }
            })
            .catch(error => {
                // Restore button if failed
                this.style.opacity = '1';
                this.textContent = 'Delete Review';
                alert('Error deleting review. Please try again.');
            });
        });
    }
    @endauth

    // Toast Notification System
    function showToast(message, type = 'success') {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed bottom-4 left-4 z-50 space-y-3';
            toastContainer.style.pointerEvents = 'none'; // Allow clicks through container
            document.body.appendChild(toastContainer);
        }

        // Determine colors and icons based on type
        let borderColor, iconColor, icon;
        
        switch(type) {
            case 'remove':
            case 'delete':
            case 'clear':
                borderColor = 'border-red-400';
                iconColor = 'text-red-500';
                icon = `<svg class="w-5 h-5 ${iconColor} mr-3 flex-shrink-0 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>`;
                break;
            case 'add-wishlist':
                borderColor = 'border-orange-400';
                iconColor = 'text-orange-500';
                icon = `<svg class="w-5 h-5 ${iconColor} mr-3 flex-shrink-0 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                </svg>`;
                break;
            case 'error':
                borderColor = 'border-red-500';
                iconColor = 'text-red-500';
                icon = `<svg class="w-5 h-5 ${iconColor} mr-3 flex-shrink-0 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>`;
                break;
            case 'success':
            default:
                borderColor = 'border-green-500';
                iconColor = 'text-green-500';
                icon = `<svg class="w-5 h-5 ${iconColor} mr-3 flex-shrink-0 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>`;
                break;
        }

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `bg-white border-l-4 ${borderColor} rounded-lg shadow-lg p-4 min-w-80 max-w-md transform transition-all duration-500 ease-out cursor-pointer`;
        toast.style.pointerEvents = 'auto'; // Enable clicks on toast
        
        // Start with hidden state
        toast.style.transform = 'translateX(-100%) scale(0.8)';
        toast.style.opacity = '0';

        toast.innerHTML = `
            <div class="flex items-start">
                ${icon}
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <button class="ml-4 text-gray-400 hover:text-gray-600 transition-colors duration-200 hover:scale-110 transform">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

        // Add hover effects
        toast.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(0) scale(1.02)';
            this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
            const icon = this.querySelector('svg');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });

        toast.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0) scale(1)';
            this.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
            const icon = this.querySelector('svg');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });

        // Add click to dismiss
        toast.addEventListener('click', function() {
            dismissToast(toast);
        });

        // Add to container
        toastContainer.appendChild(toast);

        // Animate in with delay for smoother effect
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.style.transform = 'translateX(0) scale(1)';
                toast.style.opacity = '1';
            });
        });

        // Auto dismiss after 3.5 seconds
        setTimeout(() => {
            dismissToast(toast);
        }, 3500);
    }

    function dismissToast(toast) {
        if (toast && toast.parentNode) {
            // Add dismissing class for animation
            toast.style.transform = 'translateX(-100%) scale(0.8)';
            toast.style.opacity = '0';
            
            // Add a subtle rotation on dismiss
            setTimeout(() => {
                if (toast.style.transform) {
                    toast.style.transform += ' rotate(-2deg)';
                }
            }, 100);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 500); // Increased timeout to match animation duration
        }
    }

    // Check for notification messages from Laravel session or localStorage
    @if(session('notification'))
        window.addEventListener('load', function() {
            showToast('{{ session('notification.message') }}', '{{ session('notification.type', 'success') }}');
        });
    @endif

    // Check for localStorage notifications (for actions that refresh the page)
    window.addEventListener('load', function() {
        const storedNotification = localStorage.getItem('bookphile_notification');
        if (storedNotification) {
            try {
                const notification = JSON.parse(storedNotification);
                showToast(notification.message, notification.type || 'success');
                localStorage.removeItem('bookphile_notification');
            } catch (e) {
                console.error('Error parsing stored notification:', e);
                localStorage.removeItem('bookphile_notification');
            }
        }
    });
});
</script>
@endsection
