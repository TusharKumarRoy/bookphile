<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['readingStatuses'])
                    ->latest()
                    ->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['readingStatuses.book']);
        
        $readingStats = $user->getReadingStats();
        
        return view('admin.users.show', compact('user', 'readingStats'));
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting other admins (only master admins can delete admins)
        if ($user->isAdmin() && !auth()->user()->isMasterAdmin()) {
            return back()->with('error', 'Only master admins can delete admin accounts.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}