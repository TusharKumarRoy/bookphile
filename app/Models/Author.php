<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'first_name',
        'last_name',
        'biography',
        'birth_date',
        'death_date',
        'image',
        'nationality',
    ];

    
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'death_date' => 'date',
        ];
    }

    
    public function books()
    {
        return $this->belongsToMany(Book::class)->withTimestamps();
    }

    
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    
    public function isAlive(): bool
    {
        return is_null($this->death_date);
    }

    
    public function getAge(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }

        $endDate = $this->death_date ?: now();
        return (int) $this->birth_date->diffInYears($endDate);
    }

    
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

    
    public function hasImage(): bool
    {
        return !empty($this->image);
    }
}