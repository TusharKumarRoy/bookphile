<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['authors', 'genres']);
        
        // Search by title or author
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('authors', function($authorQuery) use ($search) {
                      $authorQuery->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by genre
        if ($genreId = $request->get('genre')) {
            $query->whereHas('genres', function($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }
        
        // Filter by author
        if ($authorId = $request->get('author')) {
            $query->whereHas('authors', function($q) use ($authorId) {
                $q->where('authors.id', $authorId);
            });
        }
        
        // Sort options
        $sort = $request->get('sort', 'title');
        switch ($sort) {
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'year':
                $query->orderBy('publication_date', 'desc');
                break;
            case 'pages':
                $query->orderBy('page_count', 'desc');
                break;
            default:
                $query->orderBy('title');
        }
        
        $books = $query->get();
        $genres = Genre::orderBy('name')->get();
        $authors = Author::all()->sortBy(function($author) {
            return $author->getFullNameAttribute();
        });
        
        return view('books.index', compact('books', 'genres', 'authors'));
    }
    
    public function show(Book $book)
    {
        $book->load(['authors', 'genres']);
        
        // Load user-specific data if authenticated
        $userInteractions = [];
        if (auth()->check()) {
            $userId = auth()->id();
            
            // Get user's current rating
            $userRating = $book->getUserRating($userId);
            $userInteractions['rating'] = $userRating ? $userRating->rating : null;
            
            // Get user's review
            $userReview = $book->getUserReview($userId);
            $userInteractions['review'] = $userReview;
            
            // Get user's reading status
            $readingStatus = $book->getUserReadingStatus($userId);
            $userInteractions['reading_status'] = $readingStatus;
            
            // Check if book is in wishlist
            $userInteractions['is_in_wishlist'] = $book->isInUserWishlist($userId);
        }
        
        // Get related books
        $relatedBooks = Book::whereHas('genres', function($q) use ($book) {
            $q->whereIn('genres.id', $book->genres->pluck('id'));
        })
        ->where('id', '!=', $book->id)
        ->with(['authors', 'genres'])
        ->limit(6)
        ->get();
        
        // Get sample community reviews for display
        $communityReviews = $book->userReviews()
                                ->with('user')
                                ->orderBy('likes_count', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        // Get all ratings and reviews for the book
        $allRatings = $book->userRatings()
                          ->with('user')
                          ->orderBy('created_at', 'desc')
                          ->get();

        $allReviews = $book->userReviews()
                          ->with('user')
                          ->orderBy('created_at', 'desc')
                          ->get();

        // Organize reviews: user's review first, then others
        $organizedReviews = collect();
        $userReview = null;
        
        if (auth()->check()) {
            $userReview = $allReviews->where('user_id', auth()->id())->first();
            if ($userReview) {
                $organizedReviews->push($userReview);
            }
        }
        
        // Add other users' reviews
        $otherReviews = $allReviews->where('user_id', '!=', auth()->id() ?? 0);
        $organizedReviews = $organizedReviews->concat($otherReviews);

        // Get reading statistics
        $readingStats = $book->getReadingStats();
        
        // Get rating distribution
        $ratingDistribution = $book->getRatingDistribution();
        
        return view('books.show', compact(
            'book', 
            'relatedBooks', 
            'userInteractions', 
            'communityReviews',
            'allRatings',
            'organizedReviews',
            'readingStats',
            'ratingDistribution'
        ));
    }
}