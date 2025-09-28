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
        'name',
        'biography',
        'birth_date',
        'death_date',
        'image',
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
}