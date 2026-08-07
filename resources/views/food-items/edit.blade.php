<!-- resources/views/food/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit') }}: {{ $foodItem->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('food-items.update', $foodItem) }}">
                        @csrf <!-- CSRF Protection -->
                        @method('PUT') <!-- Specify the method is PUT for updating -->

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $foodItem->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Price -->
                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price', $foodItem->price)" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <!-- Stock Quantity -->
                        <div class="mt-4">
                            <x-input-label for="stock_quantity" :value="__('Stock Quantity')" />
                            <x-text-input id="stock_quantity" class="block mt-1 w-full" type="number" name="stock_quantity" :value="old('stock_quantity', $foodItem->stock_quantity)" required />
                            <x-input-error :messages="$errors->get('stock_quantity')" class="mt-2" />
                        </div>

                        <!-- Image Upload -->
                        <div class="mt-4">
                            <x-input-label for="image_url" :value="__('Image URL')" />
                            <input id="image_url" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                   type="url" name="image_url" value="{{ old('image_url', $foodItem->image_url) }}" required />
                            <x-input-error :messages="$errors->get('image_url')" class="mt-2" />

                            <div class="mt-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Current Image:</span>
                                <img src="{{ $foodItem->image_url }}" alt="{{ $foodItem->name }}" class="w-24 h-24 object-cover mt-1 rounded">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Item') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>