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
                                <a href="{{ route('food-items.show', $item) }}"
                                    class="block border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                                    <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}"
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
                                            <span class="text-sm">Stock: {{ $item->stock_quantity }}</span>
                                            <span class="text-lg font-semibold">BDT
                                                {{ number_format($item->price, 2) }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
