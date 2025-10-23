<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReview extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'review_text',
        'is_spoiler',
        'likes_count',
    ];

    protected $casts = [
        'is_spoiler' => 'boolean',
        'likes_count' => 'integer',
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
            'review_text' => 'required|string|min:10|max:5000',
            'is_spoiler' => 'boolean',
        ];
    }

   
    public function getTruncatedReviewAttribute($length = 300): string
    {
        return strlen($this->review_text) > $length 
            ? substr($this->review_text, 0, $length) . '...' 
            : $this->review_text;
    }
}
