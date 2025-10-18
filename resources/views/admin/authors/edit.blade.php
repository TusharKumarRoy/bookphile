@extends('admin.layout')

@section('title', 'Edit Author')

@section('content')
<div class="mb-8">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-900">Edit Author: {{ $author->first_name }} {{ $author->last_name }}</h2>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <form action="{{ route('admin.authors.update', $author) }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
        @csrf
        @method('PATCH')
        
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $author->first_name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('first_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $author->last_name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('last_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="birth_date" class="block text-sm font-medium text-gray-700">Birth Date</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $author->birth_date?->format('Y-m-d')) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('birth_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="death_date" class="block text-sm font-medium text-gray-700">Death Date</label>
                <input type="date" name="death_date" id="death_date" value="{{ old('death_date', $author->death_date?->format('Y-m-d')) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('death_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-3">Author Image</label>

                <!-- Current Image Preview -->
                @if($author->image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <div class="w-24 h-32 bg-gray-100 border border-gray-300 rounded-lg overflow-hidden">
                            <img src="{{ $author->image_url }}" alt="Current author image" class="w-full h-full object-cover"
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'%3E%3Crect width=\'100\' height=\'100\' fill=\'%23f3f4f6\'/%3E%3Ctext x=\'50\' y=\'50\' font-family=\'Arial\' font-size=\'40\' fill=\'%236b7280\' text-anchor=\'middle\' dy=\'0.3em\'%3E📚%3C/text%3E%3C/svg%3E'">
                        </div>
                    </div>
                @endif

                <!-- New Image Preview -->
                <div class="mb-4 flex justify-center">
                    <div id="image_preview_container" class="hidden">
                        <p class="text-sm text-gray-600 mb-2 text-center">New Image Preview:</p>
                        <img id="image_preview" src="" alt="New Image Preview" class="w-48 h-64 object-cover rounded-lg border-2 border-gray-300">
                    </div>
                </div>

                <!-- Image Upload Type Selection -->
                <div class="space-y-4">
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="image_type" value="url" class="form-radio text-blue-600" {{ old('image_type', 'url') === 'url' ? 'checked' : '' }} onchange="toggleImageInput()">
                            <span class="ml-2 text-sm text-gray-700">Use Image URL</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="image_type" value="file" class="form-radio text-blue-600" {{ old('image_type') === 'file' ? 'checked' : '' }} onchange="toggleImageInput()">
                            <span class="ml-2 text-sm text-gray-700">Upload New Image File</span>
                        </label>
                    </div>

                    <!-- URL Input -->
                    <div id="url_input" class="block">
                        @php
                            $currentImageUrl = '';
                            if ($author->image && (str_starts_with($author->image, 'http://') || str_starts_with($author->image, 'https://'))) {
                                $currentImageUrl = $author->image;
                            }
                        @endphp
                        <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $currentImageUrl) }}"
                               placeholder="https://example.com/author-photo.jpg"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               onchange="previewImageUrl()">
                    </div>

                    <!-- File Input -->
                    <div id="file_input" class="hidden">
                        <input type="file" name="image_file" id="image_file" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               onchange="previewImageFile()">
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>

                @error('image_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('image_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="biography" class="block text-sm font-medium text-gray-700">Biography</label>
                <textarea name="biography" id="biography" rows="6"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('biography', $author->biography) }}</textarea>
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
                    onclick="return validateForm()"
                    class="border border-black bg-white text-black font-bold py-2 px-4 rounded hover:bg-black hover:text-white hover:-translate-y-0.5 transition-all duration-200">
                Update Author
            </button>
        </div>
    </form>
</div>

<script>
function toggleImageInput() {
    const urlRadio = document.querySelector('input[name="image_type"][value="url"]');
    const fileRadio = document.querySelector('input[name="image_type"][value="file"]');
    const urlInput = document.getElementById('url_input');
    const fileInput = document.getElementById('file_input');
    
    if (urlRadio.checked) {
        urlInput.classList.remove('hidden');
        urlInput.classList.add('block');
        fileInput.classList.remove('block');
        fileInput.classList.add('hidden');
        // Clear file input when switching to URL
        const fileEl = document.getElementById('image_file');
        if (fileEl) fileEl.value = '';
        hideImagePreview();
    } else if (fileRadio.checked) {
        fileInput.classList.remove('hidden');
        fileInput.classList.add('block');
        urlInput.classList.remove('block');
        urlInput.classList.add('hidden');
        // Clear URL input when switching to file
        const urlEl = document.getElementById('image_url');
        if (urlEl) urlEl.value = '';
        hideImagePreview();
    }
}

function previewImageUrl() {
    const urlInput = document.getElementById('image_url');
    const url = urlInput.value.trim();
    
    // Check URL length (database limit is 255 characters)
    if (url && url.length > 255) {
        alert(`⚠️ Image URL is too long!\n\nURL length: ${url.length} characters\nMaximum allowed: 255 characters\n\nPlease use a shorter URL or upload the image file instead.`);
        urlInput.value = '';
        hideImagePreview();
        return;
    }
    
    if (url) {
        const preview = document.getElementById('image_preview');
        const container = document.getElementById('image_preview_container');
        
        preview.src = url;
        container.classList.remove('hidden');
        
        // Handle load error
        preview.onerror = function() {
            hideImagePreview();
        };
    } else {
        hideImagePreview();
    }
}

function previewImageFile() {
    const fileInput = document.getElementById('image_file');
    const file = fileInput.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image_preview');
            const container = document.getElementById('image_preview_container');
            
            preview.src = e.target.result;
            container.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        hideImagePreview();
    }
}

function hideImagePreview() {
    const container = document.getElementById('image_preview_container');
    container.classList.add('hidden');
}

function validateForm() {
    const urlRadio = document.querySelector('input[name="image_type"][value="url"]');
    const urlInput = document.getElementById('image_url');
    
    // Check if URL option is selected and URL is filled
    if (urlRadio.checked && urlInput.value.trim()) {
        const url = urlInput.value.trim();
        if (url.length > 255) {
            alert(`⚠️ Image URL is too long!\n\nURL length: ${url.length} characters\nMaximum allowed: 255 characters\n\nPlease use a shorter URL or upload the image file instead.`);
            urlInput.focus();
            return false;
        }
    }
    
    return true;
}
</script>
@endsection