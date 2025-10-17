<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BookTracker') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Check if this is a book page or regular Breeze page -->
        @if(request()->routeIs('books.*') || request()->routeIs('authors.*') || request()->routeIs('genres.*') || request()->routeIs('home'))
            <!-- Custom navigation for book pages -->
            <nav class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center text-xl font-bold text-blue-600">
                                <div class="w-8 h-8 mr-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                Bookphile
                            </a>
                        </div>
                        
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="{{ route('books.index') }}" class="text-gray-700 hover:text-blue-600">Books</a>
                            <a href="{{ route('authors.index') }}" class="text-gray-700 hover:text-blue-600">Authors</a>
                            <a href="{{ route('genres.index') }}" class="text-gray-700 hover:text-blue-600">Genres</a>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ route('users.show', auth()->user()) }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-700 hover:text-blue-600">Logout</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Sign Up</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content for book pages -->
            <main>
                @yield('content')
            </main>
        @else
            <!-- Original Breeze layout for dashboard, profile, etc. -->
            <div class="min-h-screen bg-gray-100">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        @endif

        <!-- Global Admin Keyboard Shortcut -->
        @auth
            @if(auth()->user()->isAdmin())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Global keyboard shortcut for admin dashboard (Ctrl + Alt + .)
                        document.addEventListener('keydown', function(event) {
                            // Check for Ctrl + Alt + . (period/dot)
                            if (event.ctrlKey && event.altKey && event.key === '.') {
                                event.preventDefault();
                                
                                // Add visual feedback
                                const body = document.body;
                                body.style.transition = 'opacity 0.2s ease';
                                body.style.opacity = '0.8';
                                
                                // Navigate to admin dashboard
                                setTimeout(() => {
                                    window.location.href = '{{ route("admin.dashboard") }}';
                                }, 100);
                            }
                        });
                        
                        // Optional: Show shortcut hint on page load (only for admins)
                        console.log('🔑 Admin shortcut: Ctrl + Alt + . to go to Admin Dashboard');
                    });
                </script>
            @endif
        @endauth
    </body>
</html>