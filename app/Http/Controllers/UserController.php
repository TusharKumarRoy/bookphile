<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function show(User $user)
    {
        // Load user's reading statistics and recent activity
        $user->load([
            'readingStatuses.book.authors',
            'readingStatuses.book.genres'
        ]);
        
        // Get reading statistics
        $readingStats = $user->getReadingStats();
        
        // Get wishlist count
        $wishlistCount = $user->wishlist()->count();
        
        // Get user's recent reviews and ratings
        $userRatings = $user->ratings()
                           ->with(['book.authors', 'book.genres'])
                           ->latest()
                           ->take(10)
                           ->get();
        
        $userReviews = $user->reviews()
                           ->with(['book.authors', 'book.genres'])
                           ->latest()
                           ->take(10)
                           ->get();
        
        // Combine reviews and ratings for display
        $recentActivity = collect()
            ->merge($userReviews->map(function($review) {
                return [
                    'type' => 'review',
                    'data' => $review,
                    'created_at' => $review->created_at
                ];
            }))
            ->merge($userRatings->whereNotIn('book_id', $userReviews->pluck('book_id'))->map(function($rating) {
                return [
                    'type' => 'rating',
                    'data' => $rating,
                    'created_at' => $rating->created_at
                ];
            }))
            ->sortByDesc('created_at')
            ->take(10);
        
        return view('users.show', compact('user', 'readingStats', 'wishlistCount', 'recentActivity'));
    }
    
    public function getWantToReadBooks(User $user)
    {
        $books = $user->readingStatuses()
                     ->where('status', 'want_to_read')
                     ->with(['book.authors', 'book.genres'])
                     ->get()
                     ->pluck('book');
        
        return response()->json($books);
    }
    
    public function getCurrentlyReadingBooks(User $user)
    {
        $books = $user->readingStatuses()
                     ->where('status', 'currently_reading')
                     ->with(['book.authors', 'book.genres'])
                     ->get()
                     ->pluck('book');
        
        return response()->json($books);
    }
    
    public function getFinishedReadingBooks(User $user)
    {
        $books = $user->readingStatuses()
                     ->where('status', 'finished_reading')
                     ->with(['book.authors', 'book.genres'])
                     ->get()
                     ->pluck('book');
        
        return response()->json($books);
    }
    
    public function getWishlistBooks(User $user)
    {
        $books = $user->wishlist()
                     ->with(['authors', 'genres'])
                     ->get();
        
        return response()->json($books);
    }
    
    public function edit(User $user)
    {
        // Check if user can edit this profile (only own profile)
        if (auth()->id() !== $user->id) {
            abort(403, 'You can only edit your own profile.');
        }
        
        return view('users.edit', compact('user'));
    }
    
    public function update(Request $request, User $user)
    {
        // Check if user can update this profile (only own profile)
        if (auth()->id() !== $user->id) {
            abort(403, 'You can only edit your own profile.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists
            if ($user->profile_image && file_exists(public_path('storage/' . $user->profile_image))) {
                unlink(public_path('storage/' . $user->profile_image));
            }
            
            // Store new profile image
            $imagePath = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $imagePath;
        }
        
        // Update user
        $user->update($validated);
        
        return redirect()->route('users.show', $user)->with('success', 'Profile updated successfully!');
    }
    
    public function settings(User $user)
    {
        // Check if user can access this settings page (only own profile)
        if (auth()->id() !== $user->id) {
            abort(403, 'You can only access your own settings.');
        }
        
        return view('users.settings', compact('user'));
    }
    
    public function updateEmail(Request $request, User $user)
    {
        // Check if user can update this profile (only own profile)
        if (auth()->id() !== $user->id) {
            abort(403, 'You can only update your own email.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        // Update email
        $user->update([
            'email' => $validated['email'],
            // TODO: Add email_verified_at => null when email verification is implemented
        ]);
        
        return redirect()->route('users.settings', $user)->with('success', 'Email address updated successfully! (Email verification feature coming soon)');
    }
    
    public function updatePassword(Request $request, User $user)
    {
        // Check if user can update this profile (only own profile)
        if (auth()->id() !== $user->id) {
            abort(403, 'You can only update your own password.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ]);
        
        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors([
                'current_password' => 'The current password is incorrect.'
            ]);
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('users.settings', $user)->with('password_success', 'Password changed successfully!');
    }

    public function toggleEmailVisibility(Request $request, User $user)
    {
        // Check if user can update this profile (only own profile)
        if (auth()->id() !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Toggle email visibility
        $user->update([
            'email_visible' => !$user->email_visible,
        ]);
        
        return response()->json([
            'success' => true,
            'email_visible' => $user->email_visible,
            'status' => $user->email_visible ? 'Public' : 'Private'
        ]);
    }
}
