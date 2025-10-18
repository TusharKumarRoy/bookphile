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
                        <img src="{{ $author->image_url }}" alt="Current author image" class="w-24 h-24 rounded-lg object-cover">
                    </div>
                @endif
                
                <!-- New Image Preview -->
                <div class="mb-4 flex justify-center">
                    <div id="image_preview_container" class="hidden">
                        <p class="text-sm text-gray-600 mb-2 text-center">New Image Preview:</p>
                        <img id="image_preview" src="" alt="New Image Preview" class="w-48 h-48 object-cover rounded-lg border-2 border-gray-300">
                    </div>
                </div>
                
                <!-- Image Upload Options -->
                <div class="space-y-4 border border-gray-300 rounded-lg p-4">
                    <div>
                        <label class="flex items-center">
                            <input type="radio" name="image_type" value="url" class="mr-2" {{ old('image_type', 'url') === 'url' ? 'checked' : '' }} onchange="toggleImageType()">
                            <span class="text-sm font-medium">Use Image URL</span>
                        </label>
                        <div class="mt-2 ml-6">
                            @php
                                $currentImageUrl = '';
                                if ($author->image && (str_starts_with($author->image, 'http://') || str_starts_with($author->image, 'https://'))) {
                                    $currentImageUrl = $author->image;
                                }
                            @endphp
                            <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $currentImageUrl) }}"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://example.com/image.jpg"
                                   onchange="previewImageUrl()">
                        </div>
                    </div>
                    
                    <div>
                        <label class="flex items-center">
                            <input type="radio" name="image_type" value="file" class="mr-2" {{ old('image_type') === 'file' ? 'checked' : '' }} onchange="toggleImageType()">
                            <span class="text-sm font-medium">Upload Image File</span>
                        </label>
                        <div class="mt-2 ml-6">
                            <input type="file" name="image_file" id="image_file" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   onchange="previewImageFile()">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
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
function toggleImageType() {
    const urlRadio = document.querySelector('input[name="image_type"][value="url"]');
    const fileRadio = document.querySelector('input[name="image_type"][value="file"]');
    
    if (urlRadio.checked) {
        document.getElementById('image_file').value = '';
        hideImagePreview();
    } else if (fileRadio.checked) {
        document.getElementById('image_url').value = '';
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