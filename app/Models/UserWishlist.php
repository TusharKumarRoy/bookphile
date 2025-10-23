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

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

  
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public static function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ];
    }

    
    public static function isInWishlist(int $userId, int $bookId): bool
    {
        return self::where('user_id', $userId)
                   ->where('book_id', $bookId)
                   ->exists();
    }

 
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
