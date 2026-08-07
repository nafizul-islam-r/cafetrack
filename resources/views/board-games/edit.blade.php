<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit') }}: {{ $boardGame->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('board-games.update', $boardGame) }}">
                        @csrf <!-- CSRF Protection -->
                        @method('PUT') <!-- Specify the method is PUT for updating -->

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Game Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $boardGame->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Total Units -->
                        <div class="mt-4">
                            <x-input-label for="total_units" :value="__('Total Units')" />
                            <x-text-input id="total_units" class="block mt-1 w-full" type="number" name="total_units" :value="old('total_units', $boardGame->total_units)" required />
                            <x-input-error :messages="$errors->get('total_units')" class="mt-2" />
                        </div>

                        <!-- Available Units -->
                        <div class="mt-4">
                            <x-input-label for="available_units" :value="__('Available Units')" />
                            <x-text-input id="available_units" class="block mt-1 w-full" type="number" name="available_units" :value="old('available_units', $boardGame->available_units)" required />
                            <x-input-error :messages="$errors->get('available_units')" class="mt-2" />
                        </div>

                        <!-- Image Upload -->
                        <div class="mt-4">
                            <x-input-label for="image_url" :value="__('Image URL')" />
                            <input id="image_url" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                   type="url" name="image_url" value="{{ old('image_url', $boardGame->image_url) }}" required />
                            <x-input-error :messages="$errors->get('image_url')" class="mt-2" />

                            <div class="mt-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Current Image:</span>
                                <img src="{{ $boardGame->image_url }}" alt="{{ $boardGame->name }}" class="w-24 h-24 object-cover mt-1 rounded">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Game') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>