<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReadingStatus extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'started_reading',
        'finished_reading',
        'current_page',
        'notes',
        'is_favorite'
    ];

 
    protected $casts = [
        'started_reading' => 'date',
        'finished_reading' => 'date',
        'is_favorite' => 'boolean',
    ];

  
    const STATUS_WANT_TO_READ = 'want_to_read';
    const STATUS_CURRENTLY_READING = 'currently_reading';
    const STATUS_FINISHED_READING = 'finished_reading';

   
    public static function getStatuses(): array
    {
        return [
            self::STATUS_WANT_TO_READ => 'Want to Read',
            self::STATUS_CURRENTLY_READING => 'Currently Reading',
            self::STATUS_FINISHED_READING => 'Finished Reading',
        ];
    }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeWantToRead($query)
    {
        return $query->where('status', self::STATUS_WANT_TO_READ);
    }

    public function scopeCurrentlyReading($query)
    {
        return $query->where('status', self::STATUS_CURRENTLY_READING);
    }

    public function scopeFinishedReading($query)
    {
        return $query->where('status', self::STATUS_FINISHED_READING);
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Helper methods
     */
    public function getReadingProgress(): ?float
    {
        if ($this->current_page && $this->book->page_count) {
            return min(($this->current_page / $this->book->page_count) * 100, 100);
        }
        return null;
    }

    public function getReadingDuration(): ?int
    {
        if ($this->started_reading && $this->finished_reading) {
            return $this->started_reading->diffInDays($this->finished_reading);
        }
        return null;
    }

    public function isCurrentlyReading(): bool
    {
        return $this->status === self::STATUS_CURRENTLY_READING;
    }

    public function isFinished(): bool
    {
        return $this->status === self::STATUS_FINISHED_READING;
    }

    public function getStatusLabel(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Auto-set dates when status changes
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($readingStatus) {
            // Auto-set started_reading when status becomes currently_reading
            if ($readingStatus->status === self::STATUS_CURRENTLY_READING && !$readingStatus->started_reading) {
                $readingStatus->started_reading = now()->toDateString();
            }

            // Auto-set finished_reading when status becomes finished_reading
            if ($readingStatus->status === self::STATUS_FINISHED_READING && !$readingStatus->finished_reading) {
                $readingStatus->finished_reading = now()->toDateString();
                // Set current_page to total pages when finished
                if ($readingStatus->book && $readingStatus->book->page_count) {
                    $readingStatus->current_page = $readingStatus->book->page_count;
                }
            }
        });
    }
}