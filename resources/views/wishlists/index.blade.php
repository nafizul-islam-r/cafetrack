<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Wishlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($wishlists->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No items</h3>
                            <p class="mt-1 text-sm text-gray-500">Your wishlist is empty.</p>
                            <div class="mt-6">
                                <a href="{{ route('food-items.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                    Browse Menu
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($wishlists as $wishlist)
                                <div class="relative group border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                                    <a href="{{ route('food-items.show', $wishlist->food_item_id) }}" class="block">
                                        <img src="{{ $wishlist->food_item_image_url }}" alt="{{ $wishlist->food_item_name }}" class="w-full h-48 object-cover">
                                        <div class="p-4">
                                            <h3 class="font-bold text-lg">{{ $wishlist->food_item_name }}</h3>
                                            
                                            <div class="flex justify-between items-end mt-2">
                                                @if($wishlist->foodItem && $wishlist->foodItem->stock_quantity > 0)
                                                    <span class="text-sm text-green-600 font-medium">In Stock</span>
                                                @else
                                                    <span class="text-sm text-red-500 font-medium">Out of Stock</span>
                                                @endif
                                                <span class="text-lg font-semibold">BDT {{ number_format($wishlist->food_item_price, 2) }}</span>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="absolute top-2 right-2 z-10">
                                        <form action="{{ route('wishlists.destroy', $wishlist) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Remove from Wishlist" class="p-2 bg-white rounded-full shadow text-red-500 hover:text-red-700 transition-colors">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
