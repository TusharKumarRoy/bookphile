<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\UserReview;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookReviewController extends Controller
{
    /**
     * Store a new review for a book.
     */
    public function store(Request $request, Book $book): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'review_text' => 'required|string|min:10|max:5000',
            'is_spoiler' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = Auth::id();

        // Check if user already has a review for this book
        $existingReview = UserReview::where('user_id', $userId)
                                   ->where('book_id', $book->id)
                                   ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this book. Use update instead.',
            ], 409);
        }

        $review = UserReview::create([
            'user_id' => $userId,
            'book_id' => $book->id,
            'review_text' => $request->input('review_text'),
            'is_spoiler' => $request->boolean('is_spoiler', false),
        ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Review posted successfully',
            'data' => [
                'review' => [
                    'id' => $review->id,
                    'review_text' => $review->review_text,
                    'is_spoiler' => $review->is_spoiler,
                    'likes_count' => $review->likes_count,
                    'created_at' => $review->created_at->format('M j, Y'),
                    'user' => [
                        'name' => $review->user->getFullNameAttribute(),
                    ],
                ],
            ],
        ], 201);
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'review_text' => 'required|string|min:10|max:5000',
            'is_spoiler' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = Auth::id();
        $review = UserReview::where('user_id', $userId)
                           ->where('book_id', $book->id)
                           ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found',
            ], 404);
        }

        $review->update([
            'review_text' => $request->input('review_text'),
            'is_spoiler' => $request->boolean('is_spoiler', false),
        ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'data' => [
                'review' => [
                    'id' => $review->id,
                    'review_text' => $review->review_text,
                    'is_spoiler' => $review->is_spoiler,
                    'likes_count' => $review->likes_count,
                    'created_at' => $review->created_at->format('M j, Y'),
                    'user' => [
                        'name' => $review->user->getFullNameAttribute(),
                    ],
                ],
            ],
        ]);
    }

    /**
     * Get current user's review for a book.
     */
    public function show(Book $book): JsonResponse
    {
        $userId = Auth::id();
        $review = UserReview::where('user_id', $userId)
                           ->where('book_id', $book->id)
                           ->with('user')
                           ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'review' => $review ? [
                    'id' => $review->id,
                    'review_text' => $review->review_text,
                    'is_spoiler' => $review->is_spoiler,
                    'likes_count' => $review->likes_count,
                    'created_at' => $review->created_at->format('M j, Y'),
                    'user' => [
                        'name' => $review->user->getFullNameAttribute(),
                    ],
                ] : null,
            ],
        ]);
    }

    /**
     * Delete a review.
     */
    public function destroy(Book $book): JsonResponse
    {
        $userId = Auth::id();
        $review = UserReview::where('user_id', $userId)
                           ->where('book_id', $book->id)
                           ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found',
            ], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully',
        ]);
    }

    /**
     * Get all reviews for a book (paginated).
     */
    public function index(Book $book): JsonResponse
    {
        $reviews = UserReview::where('book_id', $book->id)
                            ->with('user')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        $reviewsData = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'review_text' => $review->getTruncatedReviewAttribute(),
                'full_review_text' => $review->review_text,
                'is_spoiler' => $review->is_spoiler,
                'likes_count' => $review->likes_count,
                'created_at' => $review->created_at->format('M j, Y'),
                'user' => [
                    'name' => $review->user->getFullNameAttribute(),
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => $reviewsData,
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'total' => $reviews->total(),
                ],
            ],
        ]);
    }
}
