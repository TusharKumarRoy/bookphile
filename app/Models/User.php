<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'bio',
        'profile_image',
        'role',
        'email_visible',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'email_visible' => 'boolean',
        ];
    }

    
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'master_admin']);
    }

   
    public function isMasterAdmin(): bool
    {
        return $this->role === 'master_admin';
    }

    
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }


public function readingStatuses()
{
    return $this->hasMany(ReadingStatus::class);
}

public function wantToReadBooks()
{
    return $this->belongsToMany(Book::class, 'reading_statuses')
                ->wherePivot('status', ReadingStatus::STATUS_WANT_TO_READ)
                ->withPivot(['status', 'started_reading', 'finished_reading', 'current_page', 'notes', 'is_favorite'])
                ->withTimestamps();
}

public function currentlyReadingBooks()
{
    return $this->belongsToMany(Book::class, 'reading_statuses')
                ->wherePivot('status', ReadingStatus::STATUS_CURRENTLY_READING)
                ->withPivot(['status', 'started_reading', 'finished_reading', 'current_page', 'notes', 'is_favorite'])
                ->withTimestamps();
}

public function finishedReadingBooks()
{
    return $this->belongsToMany(Book::class, 'reading_statuses')
                ->wherePivot('status', ReadingStatus::STATUS_FINISHED_READING)
                ->withPivot(['status', 'started_reading', 'finished_reading', 'current_page', 'notes', 'is_favorite'])
                ->withTimestamps();
}

public function favoriteBooks()
{
    return $this->belongsToMany(Book::class, 'reading_statuses')
                ->wherePivot('is_favorite', true)
                ->withPivot(['status', 'started_reading', 'finished_reading', 'current_page', 'notes', 'is_favorite'])
                ->withTimestamps();
}

// Wishlist relationship
public function wishlist()
{
    return $this->belongsToMany(Book::class, 'user_wishlists')->withTimestamps();
}

// Ratings relationship
public function ratings()
{
    return $this->hasMany(\App\Models\UserRating::class);
}

// Reviews relationship
public function reviews()
{
    return $this->hasMany(\App\Models\UserReview::class);
}


public function getReadingStatusFor(Book $book): ?ReadingStatus
{
    return $this->readingStatuses()->where('book_id', $book->id)->first();
}


public function hasRead(Book $book): bool
{
    return $this->readingStatuses()
                ->where('book_id', $book->id)
                ->where('status', ReadingStatus::STATUS_FINISHED_READING)
                ->exists();
}


public function getReadingStats(): array
{
    $statuses = $this->readingStatuses()
                     ->selectRaw('status, COUNT(*) as count')
                     ->groupBy('status')
                     ->pluck('count', 'status')
                     ->toArray();

    return [
        'want_to_read' => $statuses[ReadingStatus::STATUS_WANT_TO_READ] ?? 0,
        'currently_reading' => $statuses[ReadingStatus::STATUS_CURRENTLY_READING] ?? 0,
        'finished_reading' => $statuses[ReadingStatus::STATUS_FINISHED_READING] ?? 0,
        'total_books' => array_sum($statuses),
    ];
}
}