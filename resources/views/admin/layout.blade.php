<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin') - {{ config('app.name', 'Goodreads Clone') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <nav class="bg-white shadow-lg border-b fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <img src="{{ asset('images/bookphile_logo.png') }}" alt="Bookphile Logo" class="h-10 w-auto mr-3 object-contain">
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium border border-black {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.books.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium border border-black {{ request()->routeIs('admin.books.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Books
                    </a>
                    <a href="{{ route('admin.authors.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium border border-black {{ request()->routeIs('admin.authors.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Authors
                    </a>
                    <a href="{{ route('admin.genres.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium border border-black {{ request()->routeIs('admin.genres.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Genres
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium border border-black {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Users
                    </a>
                    <div class="border-l border-gray-300 mx-2 h-6"></div>
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium border border-black text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                        ← Back to Site
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="lg:hidden flex items-center">
                    <button id="mobile-menu-button" type="button" class="text-gray-700 hover:text-gray-900 focus:outline-none focus:text-gray-900">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden lg:hidden pb-4">
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.books.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.books.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Books
                    </a>
                    <a href="{{ route('admin.authors.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.authors.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Authors
                    </a>
                    <a href="{{ route('admin.genres.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.genres.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Genres
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">
                        Users
                    </a>
                    <div class="border-t border-gray-300 my-2"></div>
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100">
                        ← Back to Site
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Admin keyboard shortcuts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
        
        document.addEventListener('keydown', function(event) {
            // Check for Ctrl + Alt + . (period)
            if (event.ctrlKey && event.altKey && event.key === '.') {
                event.preventDefault();
                window.location.href = '{{ route('home') }}';
            }
        });
    </script>
</body>
</html>