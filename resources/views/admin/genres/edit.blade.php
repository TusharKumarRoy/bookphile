@extends('admin.layout')

@section('title', 'Edit Genre')

@section('content')
<div class="mb-8">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-900">Edit Genre: {{ $genre->name }}</h2>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <form action="{{ route('admin.genres.update', $genre) }}" method="POST" class="space-y-6 p-6">
        @csrf
        @method('PATCH')
        
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Genre Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $genre->name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-xs text-gray-500">Current slug: {{ $genre->slug }}</p>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $genre->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.genres.index') }}" 
               class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Cancel
            </a>
            <button type="submit" 
                    class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Update Genre
            </button>
        </div>
    </form>
</div>
@endsection