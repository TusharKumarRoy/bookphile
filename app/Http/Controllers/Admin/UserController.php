<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $query = User::withCount(['readingStatuses']);
        
        // Regular admins can only see regular users
        if (auth()->user()->isRegularAdmin()) {
            $query->where('role', 'user')->orWhereNull('role');
        }
        
        $users = $query->latest()->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        // Check if current user can view this user
        if (!auth()->user()->canManageUser($user) && $user->id !== auth()->id()) {
            abort(403, 'You do not have permission to view this user.');
        }
        
        $user->load(['readingStatuses.book']);
        
        $readingStats = $user->getReadingStats();
        
        return view('admin.users.show', compact('user', 'readingStats'));
    }

    public function destroy(User $user)
    {
        // Check if current user can manage this user
        if (!auth()->user()->canManageUser($user)) {
            return back()->with('error', 'You do not have permission to delete this user.');
        }

        $userName = $user->getFullNameAttribute();
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User '{$userName}' deleted successfully!");
    }

    public function edit(User $user)
    {
        // Only master admins can edit admin roles
        if (!auth()->user()->canManageUser($user) && $user->id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this user.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Check permissions
        if (!auth()->user()->canManageUser($user) && $user->id !== auth()->id()) {
            return back()->with('error', 'You do not have permission to update this user.');
        }

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,master_admin',
        ]);

        // Only master admins can change roles to admin or master_admin
        if (in_array($request->role, ['admin', 'master_admin']) && !auth()->user()->isMasterAdmin()) {
            return back()->with('error', 'Only master admins can assign admin roles.');
        }

        // Prevent regular admins from changing their own role
        if ($user->id === auth()->id() && auth()->user()->isRegularAdmin() && $request->role !== 'admin') {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update($validatedData);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    public function create()
    {
        // Only master admins can create admin accounts
        if (!auth()->user()->isMasterAdmin()) {
            abort(403, 'Only master admins can create new admin accounts.');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Only master admins can create admin accounts
        if (!auth()->user()->isMasterAdmin()) {
            abort(403, 'Only master admins can create new admin accounts.');
        }

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin,master_admin',
        ]);

        $user = User::create($validatedData);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User created successfully!');
    }
}