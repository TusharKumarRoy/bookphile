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

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 mt-16">
        {{-- Server-side flash boxes removed — toasts handle notifications now. --}}

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

        <!-- Toast Notification System (admin) -->
        <script>
            // Copy of site toast system adapted for admin layout
            function showToast(message, type = 'success') {
                let toastContainer = document.getElementById('toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'toast-container';
                    toastContainer.className = 'fixed bottom-4 left-4 z-50 space-y-3';
                    toastContainer.style.pointerEvents = 'none';
                    document.body.appendChild(toastContainer);
                }

                let borderColor, iconColor, icon;
                switch(type) {
                    case 'remove':
                    case 'delete':
                    case 'clear':
                        borderColor = 'border-red-400';
                        iconColor = 'text-red-500';
                        icon = `<svg class="w-5 h-5 ${iconColor} mr-3 flex-shrink-0 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>`;
                        break;
                    case 'error':
                        borderColor = 'border-red-500';
                        iconColor = 'text-red-500';
                        icon = `<svg class="w-5 h-5 ${iconColor} mr-3 flex-shrink-0 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>`;
                        break;
                    case 'add-wishlist':
                        borderColor = 'border-orange-400';
                        iconColor = 'text-orange-500';
                        icon = `<svg class="w-5 h-5 ${iconColor} mr-3 flex-shrink-0 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>`;
                        break;
                    case 'success':
                    default:
                        borderColor = 'border-green-500';
                        iconColor = 'text-green-500';
                        icon = `<svg class="w-5 h-5 ${iconColor} mr-3 flex-shrink-0 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`;
                        break;
                }

                const toast = document.createElement('div');
                toast.className = `bg-white border-l-4 ${borderColor} rounded-lg shadow-lg p-4 min-w-80 max-w-md transform transition-all duration-500 ease-out cursor-pointer`;
                toast.style.pointerEvents = 'auto';
                toast.style.transform = 'translateX(-100%) scale(0.8)';
                toast.style.opacity = '0';

                toast.innerHTML = `
                    <div class="flex items-start">
                        ${icon}
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${message}</p>
                        </div>
                        <button class="ml-4 text-gray-400 hover:text-gray-600 transition-colors duration-200 hover:scale-110 transform">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                `;

                toast.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(0) scale(1.02)';
                    this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)';
                    const iconEl = this.querySelector('svg');
                    if (iconEl) iconEl.style.transform = 'scale(1.1) rotate(5deg)';
                });

                toast.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0) scale(1)';
                    this.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
                    const iconEl = this.querySelector('svg');
                    if (iconEl) iconEl.style.transform = 'scale(1) rotate(0deg)';
                });

                toast.addEventListener('click', function() {
                    dismissToast(toast);
                });

                toastContainer.appendChild(toast);

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        toast.style.transform = 'translateX(0) scale(1)';
                        toast.style.opacity = '1';
                    });
                });

                setTimeout(() => {
                    dismissToast(toast);
                }, 3500);
            }

            function dismissToast(toast) {
                if (toast && toast.parentNode) {
                    toast.style.transform = 'translateX(-100%) scale(0.8)';
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        if (toast.parentNode) toast.parentNode.removeChild(toast);
                    }, 500);
                }
            }

            // Show server-side session notifications as toasts and hide their server boxes
            document.addEventListener('DOMContentLoaded', function() {
                // session('notification') preferred format: ['message'=>..., 'type'=>...]
                @if(session('notification'))
                    showToast({!! json_encode(session('notification.message')) !!}, {!! json_encode(session('notification.type', 'success')) !!});
                    // hide server boxes if present
                    document.getElementById('server-success')?.remove();
                    document.getElementById('server-error')?.remove();
                @else
                    @if(session('success'))
                        showToast({!! json_encode(session('success')) !!}, 'success');
                        document.getElementById('server-success')?.remove();
                    @endif
                    @if(session('error'))
                        showToast({!! json_encode(session('error')) !!}, 'error');
                        document.getElementById('server-error')?.remove();
                    @endif
                @endif

                // LocalStorage notifications (used for actions that trigger a full reload)
                const storedNotification = localStorage.getItem('bookphile_notification');
                if (storedNotification) {
                    try {
                        const notification = JSON.parse(storedNotification);
                        showToast(notification.message, notification.type || 'success');
                        localStorage.removeItem('bookphile_notification');
                    } catch (e) {
                        console.error('Error parsing stored notification:', e);
                        localStorage.removeItem('bookphile_notification');
                    }
                }
            });
        </script>
</body>
</html>