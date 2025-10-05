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
        @if(request()->routeIs('books.*') || request()->routeIs('home'))
            <!-- Custom navigation for book pages -->
            <nav class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                                BookTracker
                            </a>
                        </div>
                        
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="{{ route('books.index') }}" class="text-gray-700 hover:text-blue-600">Books</a>
                            <a href="#" class="text-gray-700 hover:text-blue-600">Authors</a>
                            <a href="#" class="text-gray-700 hover:text-blue-600">Genres</a>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">My Books</a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600">Admin</a>
                                @endif
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
                    {{ $slot }}
                </main>
            </div>
        @endif
    </body>
</html>