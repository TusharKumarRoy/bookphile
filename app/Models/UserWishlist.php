<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWishlist extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
    ];

    /**
     * Get the user that owns the wishlist entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book in the wishlist.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Validation rules for wishlist.
     */
    public static function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ];
    }

    /**
     * Check if a book is in user's wishlist.
     */
    public static function isInWishlist(int $userId, int $bookId): bool
    {
        return self::where('user_id', $userId)
                   ->where('book_id', $bookId)
                   ->exists();
    }

    /**
     * Add book to wishlist or toggle if already exists.
     */
    public static function toggle(int $userId, int $bookId): bool
    {
        $existing = self::where('user_id', $userId)
                       ->where('book_id', $bookId)
                       ->first();

        if ($existing) {
            $existing->delete();
            return false; // Removed from wishlist
        } else {
            self::create([
                'user_id' => $userId,
                'book_id' => $bookId,
            ]);
            return true; // Added to wishlist
        }
    }
}
