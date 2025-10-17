@extends('admin.layout')

@section('title', 'Manage Users')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
        @if(auth()->user()->isMasterAdmin())
            <a href="{{ route('admin.users.create') }}" class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Create Admin User
            </a>
        @endif
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_users']) }}</dd>
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
    
    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Master Admins</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['master_admins']) }}</dd>
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
    
    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Admins</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['admins']) }}</dd>
                    </dl>
                </div>
                <div class="ml-3 flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Users</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['regular_users']) }}</dd>
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
</div>

<!-- Search and Filters -->
<div class="bg-white shadow rounded-lg mb-6 hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Search & Filter Users</h3>
        
        <!-- Search Form -->
        <form method="GET" class="mb-6">
            <div class="flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search by name or email..." 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <button type="submit" 
                        class="border border-black bg-white text-black font-bold py-2 px-6 rounded-lg hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                    Search
                </button>
            </div>
        </form>
        
        <!-- Filter Options -->
        <div class="flex flex-wrap gap-4 items-center">
            <!-- User Type Filter -->
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <label class="text-sm font-medium text-gray-700">User Type:</label>
                <select name="role" onchange="this.form.submit()" 
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[160px]">
                    <option value="">All Users</option>
                    <option value="master_admin" {{ request('role') == 'master_admin' ? 'selected' : '' }}>Master Admins</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Regular Users</option>
                </select>
            </form>
            
            <!-- Sort Options -->
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="role" value="{{ request('role') }}">
                <label class="text-sm font-medium text-gray-700">Sort by:</label>
                <select name="sort" onchange="this.form.submit()" 
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent min-w-[200px]">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Date Joined (Newest)</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Date Joined (Oldest)</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email (A-Z)</option>
                </select>
            </form>
            
            <!-- Clear Filters -->
            @if(request()->hasAny(['search', 'role', 'sort']))
                <a href="{{ route('admin.users.index') }}" 
                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Clear All Filters
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Actions -->
@if($users->count() > 0)
<div class="bg-white shadow rounded-lg mb-6 p-4" id="bulk-actions" style="display: none;">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-gray-700">
                <span id="selected-count">0</span> users selected
            </span>
            <button type="button" onclick="selectAllUsers()" 
                    class="text-sm text-blue-600 hover:text-blue-800">
                Select All
            </button>
            <button type="button" onclick="clearSelection()" 
                    class="text-sm text-gray-600 hover:text-gray-800">
                Clear Selection
            </button>
        </div>
        <form id="bulk-delete-form" action="{{ route('admin.users.bulk-delete') }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmBulkDelete()" 
                    class="border border-red-600 bg-white text-red-600 font-bold py-2 px-4 rounded-lg hover:bg-red-600 hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Delete Selected
            </button>
        </form>
    </div>
</div>
@endif

<!-- Users Table -->
<div class="bg-white shadow overflow-hidden sm:rounded-md hover:shadow-xl hover:-translate-y-1 transform transition-all duration-300">
    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">All Users</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'role']))
                        Showing {{ $users->total() }} users matching your filters
                    @else
                        Manage user accounts and permissions
                    @endif
                </p>
            </div>
            <div class="text-sm text-gray-500">
                {{ $users->total() }} {{ Str::plural('user', $users->total()) }} total
            </div>
        </div>
    </div>
    
    @if($users->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($users as $user)
                <li class="px-4 py-4 hover:bg-gray-50 hover:scale-[1.01] transition-all duration-200 cursor-pointer" 
                    onclick="toggleRowSelection({{ $user->id }}, event)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0 flex-1">
                            <!-- Checkbox -->
                            <div class="flex-shrink-0 mr-3">
                                @if(auth()->user()->canManageUser($user))
                                    <input type="checkbox" 
                                           id="user-{{ $user->id }}"
                                           value="{{ $user->id }}" 
                                           class="user-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           onchange="updateBulkActions()"
                                           onclick="event.stopPropagation()">
                                @else
                                    <!-- Empty space to maintain alignment -->
                                    <div class="h-4 w-4"></div>
                                @endif
                            </div>
                            
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   onclick="event.stopPropagation()" 
                                   class="block">
                                    @if($user->profile_image)
                                        <img class="h-12 w-12 rounded-full object-cover hover:opacity-80 transition-opacity cursor-pointer" src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->getFullNameAttribute() }}" loading="lazy" decoding="async">
                                    @else
                                        <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center hover:opacity-80 transition-opacity cursor-pointer">
                                            <span class="text-lg font-semibold text-white">{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </a>
                            </div>
                            
                            <!-- User Details -->
                            <div class="min-w-0 flex-1 px-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           onclick="event.stopPropagation()" 
                                           class="hover:text-blue-600 transition-colors">
                                            <p class="text-sm font-medium text-gray-900 hover:text-blue-600 cursor-pointer">{{ $user->getFullNameAttribute() }}</p>
                                        </a>
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
                        <div class="flex items-center space-x-2 ml-4" onclick="event.stopPropagation()">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="text-blue-600 hover:text-blue-900 text-sm font-medium">View</a>
                            @if(auth()->user()->isMasterAdmin() && auth()->user()->canManageUser($user))
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                            @endif
                            @if(auth()->user()->canManageUser($user))
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 text-sm font-medium"
                                            onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        Delete
                                    </button>
                                </form>
                            @elseif($user->id === auth()->id())
                                <span class="text-gray-400 text-sm">You</span>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @else
        <div class="px-4 py-8 text-center">
            @if(request()->hasAny(['search', 'role']))
                <p class="text-gray-500 mb-2">No users found matching your search criteria.</p>
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-500">Clear filters</a>
                @if(auth()->user()->isMasterAdmin())
                    <span class="text-gray-500"> or </span>
                    <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:text-blue-500">create a new user</a>
                @endif
            @else
                <p class="text-gray-500">No users found.</p>
            @endif
        </div>
    @endif
</div>

<script>
function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    selectedCount.textContent = selectedCheckboxes.length;
    
    if (selectedCheckboxes.length > 0) {
        bulkActions.style.display = 'block';
    } else {
        bulkActions.style.display = 'none';
    }
}

function toggleRowSelection(userId, event) {
    // Check if the click was on a link, button, or the checkbox itself
    if (event.target.tagName.toLowerCase() === 'a' || 
        event.target.tagName.toLowerCase() === 'button' || 
        event.target.type === 'checkbox' ||
        event.target.closest('a') ||
        event.target.closest('button') ||
        event.target.closest('form')) {
        return; // Don't toggle if clicking on interactive elements
    }
    
    const checkbox = document.getElementById(`user-${userId}`);
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        updateBulkActions();
    }
}

function selectAllUsers() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateBulkActions();
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateBulkActions();
}

function confirmBulkDelete() {
    const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    
    if (selectedCount === 0) {
        alert('Please select at least one user to delete.');
        return;
    }
    
    const confirmMessage = `Are you sure you want to delete ${selectedCount} selected user${selectedCount === 1 ? '' : 's'}? This action cannot be undone.`;
    
    if (confirm(confirmMessage)) {
        const form = document.getElementById('bulk-delete-form');
        
        // Add selected user IDs to the form
        selectedCheckboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_users[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });
        
        form.submit();
    }
}

// Initialize bulk actions visibility on page load
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActions();
});
</script>
@endsection