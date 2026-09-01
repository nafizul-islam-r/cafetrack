<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Food Inventory') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @can('is-admin')
                        <div class="mb-4">
                            <a href="{{ route('food-items.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Add New Item
                            </a>
                        </div>
                    @endcan

                    @if (session('success'))
                        <div
                            class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($foodItems->isEmpty())
                        <p>No food items found.</p>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($foodItems as $item)
                                <div class="relative group border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                                    <a href="{{ route('food-items.show', $item) }}" class="block">
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
                                        class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <h3 class="font-bold text-lg">{{ $item->name }}</h3>

                                        <!-- NEW: Star Rating Display -->
                                        <div class="flex items-center mt-1">
                                            @if ($item->reviews_count > 0)
                                                <!-- Display solid stars for the rounded average rating -->
                                                @for ($i = 1; $i <= round($item->reviews_avg_rating); $i++)
                                                    <svg class="w-4 h-4 text-yellow-400 fill-current"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                    </svg>
                                                @endfor
                                                <!-- Display outline stars for the remainder -->
                                                @for ($i = round($item->reviews_avg_rating) + 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 fill-current"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                    </svg>
                                                @endfor
                                                <span
                                                    class="ml-2 text-xs text-gray-500 dark:text-gray-400">({{ $item->reviews_count }})</span>
                                            @else
                                                <span class="text-xs text-gray-500 dark:text-gray-400">No reviews
                                                    yet</span>
                                            @endif
                                        </div>

                                        <div class="flex justify-between items-end mt-2">
                                            @if($item->stock_quantity == 0)
                                                <span class="text-sm text-red-500 font-medium">Out of Stock</span>
                                            @else
                                                <span class="text-sm">Stock: {{ $item->stock_quantity }}</span>
                                            @endif
                                            <span class="text-lg font-semibold">BDT
                                                {{ number_format($item->price, 2) }}</span>
                                        </div>
                                    </div>
                                    </a>

                                    @if(!\Illuminate\Support\Facades\Gate::allows('is-admin'))
                                        <div class="absolute top-2 right-2 z-10">
                                            @if(array_key_exists($item->id, $wishlistedItems))
                                                <form action="{{ route('wishlists.destroy', $wishlistedItems[$item->id]) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Remove from Wishlist" class="p-2 bg-white rounded-full shadow text-red-500 hover:text-red-700 transition-colors">
                                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('wishlists.store') }}" method="POST" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="food_item_id" value="{{ $item->id }}">
                                                    <button type="submit" title="Add to Wishlist" class="p-2 bg-white rounded-full shadow text-gray-400 hover:text-red-500 transition-colors">
                                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                                            <path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
