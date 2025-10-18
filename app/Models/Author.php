<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'biography',
        'birth_date',
        'death_date',
        'image',
        'nationality',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'death_date' => 'date',
        ];
    }

    /**
     * Get books by this author
     */
    public function books()
    {
        return $this->belongsToMany(Book::class)->withTimestamps();
    }

    /**
     * Get the author's full name
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Check if author is still alive
     */
    public function isAlive(): bool
    {
        return is_null($this->death_date);
    }

    /**
     * Get author's age (if alive) or age at death
     */
    public function getAge(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }

        $endDate = $this->death_date ?: now();
        return (int) $this->birth_date->diffInYears($endDate);
    }

    /**
     * Get the author's image URL with fallback
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && !empty($this->image)) {
            // Check if it's already a full URL
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            // If it's a local file path, prepend storage path
            return asset('storage/' . $this->image);
        }
        
        // Generate a colored avatar with initials as fallback
        $initials = substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1);
        $colors = ['3B82F6', '10B981', 'F59E0B', 'EF4444', '8B5CF6', '06B6D4', 'F97316'];
        $colorIndex = abs(crc32($this->full_name)) % count($colors);
        $color = $colors[$colorIndex];
        
        return "https://ui-avatars.com/api/?name={$initials}&color=ffffff&background={$color}&size=256";
    }

    /**
     * Check if author has an image
     */
    public function hasImage(): bool
    {
        return !empty($this->image);
    }
}