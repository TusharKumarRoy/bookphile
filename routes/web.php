<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Redirect users to books page - more useful for a reading platform
    return redirect()->route('books.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('authors', App\Http\Controllers\Admin\AuthorController::class);
    Route::resource('genres', App\Http\Controllers\Admin\GenreController::class);
    Route::resource('books', App\Http\Controllers\Admin\BookController::class);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'store', 'edit', 'update']);
    
    // Master admin only routes
    Route::middleware('master_admin')->group(function () {
        Route::get('users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    });
});


Route::get('/books', [App\Http\Controllers\BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [App\Http\Controllers\BookController::class, 'show'])->name('books.show');

// Public routes for authors and genres
Route::get('/authors', [App\Http\Controllers\AuthorController::class, 'index'])->name('authors.index');
Route::get('/authors/{author}', [App\Http\Controllers\AuthorController::class, 'show'])->name('authors.show');

Route::get('/genres', [App\Http\Controllers\GenreController::class, 'index'])->name('genres.index');
Route::get('/genres/{genre}', [App\Http\Controllers\GenreController::class, 'show'])->name('genres.show');

// User profile routes (placeholder for future implementation)
Route::get('/users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');

// Profile editing routes (authenticated only)
Route::middleware('auth')->group(function () {
    Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    
    // Profile settings routes
    Route::get('/users/{user}/settings', [App\Http\Controllers\UserController::class, 'settings'])->name('users.settings');
    Route::put('/users/{user}/update-email', [App\Http\Controllers\UserController::class, 'updateEmail'])->name('users.update-email');
    Route::put('/users/{user}/update-password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('users.update-password');
    Route::post('/users/{user}/toggle-email-visibility', [App\Http\Controllers\UserController::class, 'toggleEmailVisibility'])->name('users.toggle-email-visibility');
});

// User book data AJAX routes - public access for viewing profiles
Route::prefix('users/{user}')->group(function () {
    Route::get('/want-to-read', [App\Http\Controllers\UserController::class, 'getWantToReadBooks'])->name('users.want-to-read');
    Route::get('/currently-reading', [App\Http\Controllers\UserController::class, 'getCurrentlyReadingBooks'])->name('users.currently-reading');
    Route::get('/finished-reading', [App\Http\Controllers\UserController::class, 'getFinishedReadingBooks'])->name('users.finished-reading');
    Route::get('/wishlist', [App\Http\Controllers\UserController::class, 'getWishlistBooks'])->name('users.wishlist');
});

// API routes for authenticated users - using web middleware for session auth
Route::middleware('auth')->prefix('api/books/{book}')->group(function () {
    // Test endpoint to verify auth is working
    Route::get('/test-auth', function () {
        return response()->json([
            'success' => true,
            'message' => 'Authentication working!',
            'user' => auth()->user()->first_name,
        ]);
    });
    
    // Rating endpoints
    Route::get('/rating', [App\Http\Controllers\Api\BookRatingController::class, 'show'])->name('api.books.rating.show');
    Route::post('/rating', [App\Http\Controllers\Api\BookRatingController::class, 'store'])->name('api.books.rating.store');
    Route::delete('/rating', [App\Http\Controllers\Api\BookRatingController::class, 'destroy'])->name('api.books.rating.destroy');
    
    // Review endpoints
    Route::get('/review', [App\Http\Controllers\Api\BookReviewController::class, 'show'])->name('api.books.review.show');
    Route::post('/review', [App\Http\Controllers\Api\BookReviewController::class, 'store'])->name('api.books.review.store');
    Route::put('/review', [App\Http\Controllers\Api\BookReviewController::class, 'update'])->name('api.books.review.update');
    Route::delete('/review', [App\Http\Controllers\Api\BookReviewController::class, 'destroy'])->name('api.books.review.destroy');
    Route::get('/reviews', [App\Http\Controllers\Api\BookReviewController::class, 'index'])->name('api.books.reviews.index');
    
    // Wishlist endpoints
    Route::get('/wishlist', [App\Http\Controllers\Api\BookWishlistController::class, 'show'])->name('api.books.wishlist.show');
    Route::post('/wishlist', [App\Http\Controllers\Api\BookWishlistController::class, 'store'])->name('api.books.wishlist.store');
    Route::delete('/wishlist', [App\Http\Controllers\Api\BookWishlistController::class, 'destroy'])->name('api.books.wishlist.destroy');
    Route::post('/wishlist/toggle', [App\Http\Controllers\Api\BookWishlistController::class, 'toggle'])->name('api.books.wishlist.toggle');
    
    // Reading status endpoints
    Route::get('/reading-status', [App\Http\Controllers\Api\ReadingStatusController::class, 'show'])->name('api.books.reading-status.show');
    Route::put('/reading-status', [App\Http\Controllers\Api\ReadingStatusController::class, 'update'])->name('api.books.reading-status.update');
    Route::delete('/reading-status', [App\Http\Controllers\Api\ReadingStatusController::class, 'destroy'])->name('api.books.reading-status.destroy');
    Route::post('/reading-status/toggle-favorite', [App\Http\Controllers\Api\ReadingStatusController::class, 'toggleFavorite'])->name('api.books.reading-status.toggle-favorite');
});

// Update the home route to show books
Route::get('/', [App\Http\Controllers\BookController::class, 'index'])->name('home');

require __DIR__.'/auth.php';