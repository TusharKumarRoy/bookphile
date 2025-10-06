<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\UserWishlist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BookWishlistController extends Controller
{
    /**
     * Toggle book in/out of wishlist.
     */
    public function toggle(Book $book): JsonResponse
    {
        $userId = Auth::id();
        $isInWishlist = UserWishlist::toggle($userId, $book->id);

        return response()->json([
            'success' => true,
            'message' => $isInWishlist ? 'Book added to wishlist' : 'Book removed from wishlist',
            'data' => [
                'is_in_wishlist' => $isInWishlist,
            ],
        ]);
    }

    /**
     * Check if book is in user's wishlist.
     */
    public function show(Book $book): JsonResponse
    {
        $userId = Auth::id();
        $isInWishlist = UserWishlist::isInWishlist($userId, $book->id);

        return response()->json([
            'success' => true,
            'data' => [
                'is_in_wishlist' => $isInWishlist,
            ],
        ]);
    }

    /**
     * Add book to wishlist.
     */
    public function store(Book $book): JsonResponse
    {
        $userId = Auth::id();
        
        // Check if already in wishlist
        if (UserWishlist::isInWishlist($userId, $book->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Book is already in your wishlist',
            ], 409);
        }

        UserWishlist::create([
            'user_id' => $userId,
            'book_id' => $book->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Book added to wishlist',
            'data' => [
                'is_in_wishlist' => true,
            ],
        ], 201);
    }

    /**
     * Remove book from wishlist.
     */
    public function destroy(Book $book): JsonResponse
    {
        $userId = Auth::id();
        
        $wishlistItem = UserWishlist::where('user_id', $userId)
                                   ->where('book_id', $book->id)
                                   ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found in wishlist',
            ], 404);
        }

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Book removed from wishlist',
            'data' => [
                'is_in_wishlist' => false,
            ],
        ]);
    }
}
