@extends('admin.layout')

@section('title', 'Add New Author')

@section('content')
<div class="mb-8">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-900">Add New Author</h2>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <form action="{{ route('admin.authors.store') }}" method="POST" class="space-y-6 p-6">
        @csrf
        
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('first_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('last_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('birth_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="death_date" class="block text-sm font-medium text-gray-700">Death Date</label>
                <input type="date" name="death_date" id="death_date" value="{{ old('death_date') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('death_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="image" class="block text-sm font-medium text-gray-700">Image URL</label>
                <input type="url" name="image" id="image" value="{{ old('image') }}"
                       placeholder="https://example.com/author-photo.jpg"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="biography" class="block text-sm font-medium text-gray-700">Biography</label>
                <textarea name="biography" id="biography" rows="6"
                          placeholder="Enter author's biography, achievements, and background..."
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('biography') }}</textarea>
                @error('biography')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.authors.index') }}" 
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Cancel
            </a>
            <button type="submit" 
                    class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Create Author
            </button>
        </div>
    </form>
</div>
@endsection