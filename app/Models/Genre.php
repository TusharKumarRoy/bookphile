<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($genre) {
            $genre->slug = Str::slug($genre->name);
        });
        
        static::updating(function ($genre) {
            $genre->slug = Str::slug($genre->name);
        });
    }

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Get books in this genre
     */
    public function books()
    {
        return $this->belongsToMany(Book::class)->withTimestamps();
    }
}