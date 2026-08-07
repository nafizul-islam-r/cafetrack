<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Board Game Inventory') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @can('is-admin')
                        <div class="mb-4">
                            <a href="{{ route('board-games.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Add New Game
                            </a>
                        </div>
                    @endcan

                    @if (session('success'))
                        <div
                            class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($boardGames->isEmpty())
                        <p>No board games found.</p>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($boardGames as $game)
                                @can('is-admin')
                                    <!-- Admin: Card is a link to the manage page -->
                                    <a href="{{ route('board-games.show', $game) }}"
                                        class="block border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                                        <img src="{{ $game->image_url }}" alt="{{ $game->name }}"
                                            class="w-full h-48 object-cover">
                                        <div class="p-4">
                                            <h3 class="font-bold text-lg">{{ $game->name }}</h3>
                                            <p
                                                class="text-sm font-semibold {{ $game->available_units > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                Available: {{ $game->available_units }} / {{ $game->total_units }}
                                            </p>
                                        </div>
                                    </a>
                                @else
                                    <!-- Regular User: Card is not a link -->
                                    <div
                                        class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-lg">
                                        <img src="{{ $game->image_url }}" alt="{{ $game->name }}"
                                            class="w-full h-48 object-cover">
                                        <div class="p-4">
                                            <h3 class="font-bold text-lg">{{ $game->name }}</h3>
                                            <p
                                                class="text-sm font-semibold {{ $game->available_units > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                Available: {{ $game->available_units }} / {{ $game->total_units }}
                                            </p>
                                        </div>
                                    </div>
                                @endcan
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
