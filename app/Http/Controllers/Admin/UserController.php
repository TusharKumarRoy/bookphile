<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['readingStatuses']);
        
        // All admins can see all user types (no restriction by default)
        // Permissions are handled in the view and actions
        
        // Search by name or email
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Filter by user type/role
        if ($role = $request->get('role')) {
            if ($role === 'user') {
                $query->where(function($q) {
                    $q->where('role', 'user')->orWhereNull('role');
                });
            } else {
                $query->where('role', $role);
            }
        }
        
        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'name':
                $query->orderBy('first_name')->orderBy('last_name');
                break;
            case 'email':
                $query->orderBy('email');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }
        
        $users = $query->paginate(20);
        
        // Calculate statistics for cards - all admins see all statistics
        $stats = [
            'total_users' => User::count(),
            'master_admins' => User::where('role', 'master_admin')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'regular_users' => User::where(function($q) {
                $q->where('role', 'user')->orWhereNull('role');
            })->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        // All admins can view any user profile (viewing is not restricted)
        // Permissions are only enforced for edit/delete operations
        
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

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected_users' => 'required|array|min:1',
            'selected_users.*' => 'exists:users,id',
        ]);

        $userIds = $request->input('selected_users');
        
        // Check permissions for each user
        $unauthorizedUsers = [];
        $protectedUsers = [];
        $validUserIds = [];
        
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (!$user) continue;
            
            // Cannot delete yourself
            if ($user->id === auth()->id()) {
                $protectedUsers[] = $user->getFullNameAttribute() . ' (yourself)';
                continue;
            }
            
            // Check if current user can manage this user
            if (!auth()->user()->canManageUser($user)) {
                $unauthorizedUsers[] = $user->getFullNameAttribute();
                continue;
            }
            
            $validUserIds[] = $userId;
        }
        
        if (!empty($unauthorizedUsers)) {
            $usersList = implode(', ', $unauthorizedUsers);
            return back()->with('error', "You do not have permission to delete: {$usersList}");
        }
        
        if (!empty($protectedUsers)) {
            $usersList = implode(', ', $protectedUsers);
            return back()->with('error', "Cannot delete: {$usersList}");
        }
        
        if (empty($validUserIds)) {
            return back()->with('error', 'No valid users selected for deletion.');
        }
        
        // Delete the users
        $deletedCount = User::whereIn('id', $validUserIds)->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Successfully deleted {$deletedCount} " . ($deletedCount === 1 ? 'user' : 'users') . '!');
    }
}