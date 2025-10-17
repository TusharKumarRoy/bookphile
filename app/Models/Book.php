<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'title',
        'isbn',
        'description',
        'publication_date',
        'page_count',
        'cover_image',
        'language',
        'average_rating',
        'ratings_count',
    ];

    
    protected function casts(): array
    {
        return [
            'publication_date' => 'date',
            'average_rating' => 'decimal:2',
        ];
    }

    
    public function authors()
    {
        return $this->belongsToMany(Author::class)->withTimestamps();
    }

    
    public function genres()
    {
        return $this->belongsToMany(Genre::class)->withTimestamps();
    }

    
    public function getMainAuthor()
    {
        return $this->authors->first();
    }

    
    public function getAuthorsStringAttribute(): string
    {
        return $this->authors->map(function($author) {
            return $author->getFullNameAttribute();
        })->implode(', ');
    }

    
    public function getGenresStringAttribute(): string
    {
        return $this->genres->pluck('name')->implode(', ');
    }

    
    public function getPublicationYearAttribute(): ?int
    {
        return $this->publication_date?->year;
    }

    
    public function hasCoverImage(): bool
    {
        return !empty($this->cover_image);
    }

    
    public function getCoverImageUrlAttribute(): string
    {
        if ($this->hasCoverImage()) {
            // Check if it's already a full URL
            if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
                return $this->cover_image;
            }
            // If it's a local file path, prepend storage path
            return asset('storage/' . $this->cover_image);
        }
        
        // Return a placeholder image
        return asset('images/book-placeholder.svg');
    }

    
    public function getFormattedRatingAttribute(): string
    {
        if ($this->ratings_count == 0) {
            return 'No ratings';
        }
        
        return number_format($this->average_rating, 2) . ' (' . $this->ratings_count . ' rating' . ($this->ratings_count != 1 ? 's' : '') . ')';
    }

    
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('isbn', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
    }

    
    public function scopeByGenre($query, $genreSlug)
    {
        return $query->whereHas('genres', function($q) use ($genreSlug) {
            $q->where('slug', $genreSlug);
        });
    }

    
    public function scopePopular($query)
    {
        return $query->where('ratings_count', '>', 0)
                    ->orderBy('average_rating', 'desc')
                    ->orderBy('ratings_count', 'desc');
    }

    
    public function scopeRecent($query)
    {
        return $query->whereNotNull('publication_date')
                    ->orderBy('publication_date', 'desc');
    }


    /**
 * Reading status relationships
 */
public function readingStatuses()
{
    return $this->hasMany(ReadingStatus::class);
}

public function readersWantToRead()
{
    return $this->belongsToMany(User::class, 'reading_statuses')
                ->wherePivot('status', ReadingStatus::STATUS_WANT_TO_READ);
}

public function readersCurrentlyReading()
{
    return $this->belongsToMany(User::class, 'reading_statuses')
                ->wherePivot('status', ReadingStatus::STATUS_CURRENTLY_READING);
}

public function readersFinished()
{
    return $this->belongsToMany(User::class, 'reading_statuses')
                ->wherePivot('status', ReadingStatus::STATUS_FINISHED_READING);
}

/**
 * User interaction relationships
 */
public function userRatings()
{
    return $this->hasMany(UserRating::class);
}

public function userReviews()
{
    return $this->hasMany(UserReview::class);
}

public function userWishlists()
{
    return $this->hasMany(UserWishlist::class);
}

/**
 * Get user's specific interactions with this book
 */
public function getUserRating($userId = null)
{
    $userId = $userId ?? auth()->id();
    if (!$userId) return null;
    
    return $this->userRatings()->where('user_id', $userId)->first();
}

public function getUserReview($userId = null)
{
    $userId = $userId ?? auth()->id();
    if (!$userId) return null;
    
    return $this->userReviews()->where('user_id', $userId)->first();
}

public function getUserReadingStatus($userId = null)
{
    $userId = $userId ?? auth()->id();
    if (!$userId) return null;
    
    return $this->readingStatuses()->where('user_id', $userId)->first();
}

public function isInUserWishlist($userId = null): bool
{
    $userId = $userId ?? auth()->id();
    if (!$userId) return false;
    
    return $this->userWishlists()->where('user_id', $userId)->exists();
}

/**
 * Get computed rating statistics
 */
public function getRatingDistribution(): array
{
    $ratings = $this->userRatings()
                   ->selectRaw('rating, COUNT(*) as count')
                   ->groupBy('rating')
                   ->pluck('count', 'rating')
                   ->toArray();

    $distribution = [];
    for ($i = 1; $i <= 5; $i++) {
        $distribution[$i] = $ratings[$i] ?? 0;
    }

    return $distribution;
}

public function updateAverageRating(): void
{
    $ratings = $this->userRatings();
    $count = $ratings->count();
    
    if ($count > 0) {
        $average = $ratings->avg('rating');
        $this->update([
            'average_rating' => round($average, 2),
            'ratings_count' => $count,
        ]);
    } else {
        $this->update([
            'average_rating' => 0,
            'ratings_count' => 0,
        ]);
    }
}

/**
 * Get reading statistics for this book
 */
public function getReadingStats(): array
{
    $statuses = $this->readingStatuses()
                     ->selectRaw('status, COUNT(*) as count')
                     ->groupBy('status')
                     ->pluck('count', 'status')
                     ->toArray();

    return [
        'want_to_read_count' => $statuses[ReadingStatus::STATUS_WANT_TO_READ] ?? 0,
        'currently_reading_count' => $statuses[ReadingStatus::STATUS_CURRENTLY_READING] ?? 0,
        'finished_reading_count' => $statuses[ReadingStatus::STATUS_FINISHED_READING] ?? 0,
        'total_readers' => array_sum($statuses),
    ];
}
}