<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark"> <!-- Added 'dark' -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CafeTrack - Board Games</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<!-- Removed all x-data, x-init, and toggle() from body -->
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <div class="min-h-screen">
        <!-- Header (Simplified) -->
        <header class="bg-white dark:bg-gray-800 shadow-md sticky w-full z-20 top-0">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('welcome') }}">
                            <img class="w-32" src="{{ asset('app-logo.png') }}" alt="CafeTrack Logo">
                        </a>
                    </div>
                    
                    <!-- Right Side (Button) -->
                    <div class="flex items-center sm:ml-6 space-x-4">
                        <!-- Theme Toggle Button Removed -->
                        
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white bg-brand-red hover:bg-opacity-90 rounded-md shadow-sm">
                            Login to Check Out
                        </a>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-extrabold text-brand-brown dark:text-white">Our Board Game Library</h1>
                    <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">See what's available to play. Log in as an admin to manage inventory.</p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if($boardGames->isEmpty())
                            <p>No board games found.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach ($boardGames as $game)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-lg">
                                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700">
                                            <img src="{{ asset('storage/' . $game->image_url) }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-lg">{{ $game->name }}</h3>
                                            <p class="text-sm font-semibold mt-2 {{ $game->available_units > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                Available: {{ $game->available_units }} / {{ $game->total_units }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-brand-brown mt-12">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <p class="text-base text-gray-400 text-center">&copy; {{ date('Y') }} CaféTrack. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>