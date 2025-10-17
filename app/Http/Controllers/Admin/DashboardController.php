<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use App\Models\ReadingStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => Book::count(),
            'total_authors' => Author::count(),
            'total_genres' => Genre::count(),
            'total_users' => User::count(),
            'books_being_read' => ReadingStatus::where('status', 'currently_reading')->count(),
            'books_finished' => ReadingStatus::where('status', 'finished_reading')->count(),
            'recent_books' => Book::with('authors')->latest()->limit(5)->get(),
            'recent_users' => User::latest()->limit(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}