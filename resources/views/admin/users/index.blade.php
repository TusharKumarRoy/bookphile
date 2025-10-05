@extends('admin.layout')

@section('title', 'Manage Users')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
    </div>
</div>

<!-- Statistics Card -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $users->total() }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Admins</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $users->where('role', '!=', 'user')->count() }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Regular Users</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $users->where('role', 'user')->count() }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Readers</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $users->where('reading_statuses_count', '>', 0)->count() }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">All Users</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage user accounts and permissions</p>
    </div>
    
    @if($users->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($users as $user)
                <li class="px-4 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="flex-shrink-0">
                                @if($user->profile_image)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ $user->profile_image }}" alt="{{ $user->getFullNameAttribute() }}">
                                @else
                                    <div class="h-12 w-12 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-lg text-gray-600">{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1 px-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->getFullNameAttribute() }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $user->role === 'master_admin' ? 'bg-red-100 text-red-800' : 
                                                   ($user->role === 'admin' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                            </span>
                                            @if($user->reading_statuses_count > 0)
                                                <span class="text-xs text-gray-500">{{ $user->reading_statuses_count }} books tracked</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">Joined {{ $user->created_at->format('M j, Y') }}</p>
                                        @if($user->email_verified_at)
                                            <p class="text-xs text-green-600">✓ Email verified</p>
                                        @else
                                            <p class="text-xs text-red-600">✗ Email not verified</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="text-blue-600 hover:text-blue-900 text-sm font-medium">View</a>
                            @if($user->id !== auth()->id() && (!$user->isAdmin() || auth()->user()->isMasterAdmin()))
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 text-sm font-medium"
                                            onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        Delete
                                    </button>
                                </form>
                            @else
                                @if($user->id === auth()->id())
                                    <span class="text-gray-400 text-sm">You</span>
                                @else
                                    <span class="text-gray-400 text-sm">Protected</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    @else
        <div class="px-4 py-8 text-center">
            <p class="text-gray-500">No users found.</p>
        </div>
    @endif
</div>
@endsection