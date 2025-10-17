@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
<div class="mb-8">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-900">Edit {{ $user->getFullNameAttribute() }}</h2>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Profile Image Display -->
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        @if($user->profile_image)
                            <img class="h-20 w-20 rounded-full object-cover" src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->getFullNameAttribute() }}" loading="lazy" decoding="async">
                        @else
                            <div class="h-20 w-20 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-semibold text-white">{{ strtoupper(substr($user->first_name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $user->getFullNameAttribute() }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400 mt-1">Profile image can be changed by the user in their profile settings</p>
                    </div>
                </div>
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name" 
                               value="{{ old('first_name', $user->first_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('first_name') border-red-300 @enderror"
                               required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" 
                               name="last_name" 
                               id="last_name" 
                               value="{{ old('last_name', $user->last_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('last_name') border-red-300 @enderror"
                               required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $user->email) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-300 @enderror"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" 
                            id="role"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('role') border-red-300 @enderror"
                            required>
                        <option value="">Select a role</option>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Regular User</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="master_admin" {{ old('role', $user->role) === 'master_admin' ? 'selected' : '' }}>Master Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        <strong>Regular User:</strong> Can read books and manage personal library<br>
                        <strong>Admin:</strong> Can manage books, authors, genres, and regular users<br>
                        <strong>Master Admin:</strong> Can manage everything including other admins
                    </p>
                </div>

                <!-- Current Role Badge -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Role</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $user->role === 'master_admin' ? 'bg-red-100 text-red-800' : 
                               ($user->role === 'admin' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Member Since</dt>
                            <dd class="text-gray-900">{{ $user->created_at->format('F j, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Email Verification</dt>
                            <dd class="text-gray-900">
                                @if($user->email_verified_at)
                                    <span class="text-green-600">✓ Verified on {{ $user->email_verified_at->format('M j, Y') }}</span>
                                @else
                                    <span class="text-red-600">✗ Not verified</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Reading Activity</dt>
                            <dd class="text-gray-900">{{ $user->readingStatuses->count() }} books tracked</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Last Updated</dt>
                            <dd class="text-gray-900">{{ $user->updated_at->format('F j, Y') }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="inline-flex items-center px-4 py-2 border border-black bg-white text-black shadow-sm text-sm font-medium rounded-md hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-black bg-white text-black shadow-sm text-sm font-medium rounded-md hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection