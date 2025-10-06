<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\UserRating;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookRatingController extends Controller
{
    /**
     * Store or update a rating for a book.
     */
    public function store(Request $request, Book $book): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = Auth::id();
        $rating = $request->input('rating');

        // Update or create rating
        $userRating = UserRating::updateOrCreate(
            [
                'user_id' => $userId,
                'book_id' => $book->id,
            ],
            [
                'rating' => $rating,
            ]
        );

        // Update book's average rating
        $book->updateAverageRating();

        return response()->json([
            'success' => true,
            'message' => 'Rating saved successfully',
            'data' => [
                'rating' => $userRating->rating,
                'average_rating' => $book->fresh()->average_rating,
                'ratings_count' => $book->fresh()->ratings_count,
            ],
        ]);
    }

    /**
     * Get current user's rating for a book.
     */
    public function show(Book $book): JsonResponse
    {
        $userId = Auth::id();
        $userRating = UserRating::where('user_id', $userId)
                                ->where('book_id', $book->id)
                                ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'user_rating' => $userRating ? $userRating->rating : null,
                'average_rating' => $book->average_rating,
                'ratings_count' => $book->ratings_count,
            ],
        ]);
    }

    /**
     * Remove a rating for a book.
     */
    public function destroy(Book $book): JsonResponse
    {
        $userId = Auth::id();
        
        $userRating = UserRating::where('user_id', $userId)
                                ->where('book_id', $book->id)
                                ->first();

        if (!$userRating) {
            return response()->json([
                'success' => false,
                'message' => 'Rating not found',
            ], 404);
        }

        $userRating->delete();
        
        // Update book's average rating
        $book->updateAverageRating();

        return response()->json([
            'success' => true,
            'message' => 'Rating removed successfully',
            'data' => [
                'average_rating' => $book->fresh()->average_rating,
                'ratings_count' => $book->fresh()->ratings_count,
            ],
        ]);
    }
}
