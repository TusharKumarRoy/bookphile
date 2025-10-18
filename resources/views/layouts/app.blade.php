<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Bookphile</title>
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

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
            <nav class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 w-full z-50">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center">
                                <img src="{{ asset('images/bookphile_logo.png') }}" alt="Bookphile Logo" class="h-10 w-auto object-contain">
                            </a>
                        </div>
                        
                        <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                            <a href="{{ route('books.index') }}" class="px-3 py-2 rounded-md text-sm font-medium border border-black {{ request()->routeIs('books.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100'  }}">Books</a>
                            <a href="{{ route('authors.index') }}" class="px-3 py-2 rounded-md text-sm font-medium border border-black {{ request()->routeIs('authors.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">Authors</a>
                            <a href="{{ route('genres.index') }}" class="px-3 py-2 rounded-md text-sm font-medium border border-black {{ request()->routeIs('genres.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100' }}">Genres</a>
                        </div>
                        
                        <div class="hidden md:flex items-center space-x-2 sm:space-x-4">
                            @auth
                                <a href="{{ route('users.show', auth()->user()) }}" class="text-sm lg:text-base text-gray-700 hover:text-blue-600 border border-black rounded px-3 py-1 hover:bg-black hover:text-white transition">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm lg:text-base text-gray-700 hover:text-blue-600 border border-black rounded px-3 py-1 hover:bg-black hover:text-white transition">Logout</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-sm lg:text-base text-gray-700 hover:text-blue-600 border border-black rounded px-3 py-1 hover:bg-black hover:text-white transition">Login</a>
                                <a href="{{ route('register') }}" class="text-sm lg:text-base text-gray-700 hover:text-blue-600 border border-black rounded px-3 py-1 hover:bg-black hover:text-white transition">Sign Up</a>
                            @endauth
                        </div>
                        
                        <!-- Mobile Menu Button -->
                        <div class="md:hidden flex items-center">
                            <button id="mobile-menu-btn" type="button" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu -->
                    <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-200 mt-2 pt-4">
                        <div class="flex flex-col space-y-3">
                            <a href="{{ route('books.index') }}" class="text-gray-700 hover:text-blue-600 px-2 py-1">Books</a>
                            <a href="{{ route('authors.index') }}" class="text-gray-700 hover:text-blue-600 px-2 py-1">Authors</a>
                            <a href="{{ route('genres.index') }}" class="text-gray-700 hover:text-blue-600 px-2 py-1">Genres</a>
                            <div class="border-t border-gray-200 my-2"></div>
                            @auth
                                <a href="{{ route('users.show', auth()->user()) }}" class="text-gray-700 hover:text-blue-600 px-2 py-1">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-gray-700 hover:text-blue-600 w-full text-left px-2 py-1">Logout</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-2 py-1">Login</a>
                                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-center">Sign Up</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content for book pages -->
            <main>
                @yield('content')
            </main>
            
            <!-- Mobile Menu Script -->
            <script>
                document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
                    const mobileMenu = document.getElementById('mobile-menu');
                    mobileMenu.classList.toggle('hidden');
                });
            </script>
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