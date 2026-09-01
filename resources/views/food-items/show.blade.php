<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-baseline">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Manage: {{ $foodItem->name }}
            </h2>

            @can('is-admin')
                <div class="flex items-baseline space-x-4">
                    <a href="{{ route('food-items.edit', $foodItem) }}"
                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-100 text-sm font-medium">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('food-items.destroy', $foodItem) }}"
                        onsubmit="return confirm('Are you sure you want to delete this item?');" class="m-0">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-100 text-sm font-medium bg-transparent border-none p-0 cursor-pointer">
                            Delete
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded shadow-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div
                    class="md:col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col space-y-6">
                    <img src="{{ $foodItem->image_url }}" alt="{{ $foodItem->name }}"
                        class="w-full h-64 object-cover rounded-lg">

                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $foodItem->name }}</h3>
                        <p class="text-2xl font-semibold mt-2 text-indigo-600 dark:text-indigo-400">
                            BDT {{ number_format($foodItem->price, 2) }}
                        </p>
                        <p
                            class="text-md mt-2 {{ $foodItem->stock_quantity > 0 ? 'text-gray-700 dark:text-gray-300' : 'text-red-600 dark:text-red-400' }}">
                            Stock: {{ $foodItem->stock_quantity }}
                        </p>

                        <div class="mt-6">
                            @unless(Auth::user()->role === 'admin')
                                <div class="flex flex-wrap gap-4 items-center">
                                    @if($foodItem->stock_quantity > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="flex items-stretch space-x-3">
                                            @csrf
                                            <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                                            <div>
                                                <label for="quantity" class="sr-only">Quantity</label>
                                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $foodItem->stock_quantity }}" class="w-20 h-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </div>
                                            <button type="submit" class="inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center justify-center px-4 py-3 rounded-md font-semibold text-xs uppercase tracking-widest bg-red-100 text-red-800 border border-transparent shadow-sm">
                                            Out of Stock
                                        </span>
                                    @endif

                                    <!-- Wishlist Button -->
                                    @if($inWishlist)
                                        <form action="{{ route('wishlists.destroy', $wishlistId) }}" method="POST" class="flex">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex justify-center items-center px-4 py-3 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm text-center">
                                                Remove from Wishlist
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('wishlists.store') }}" method="POST" class="flex">
                                            @csrf
                                            <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">
                                            <button type="submit" class="inline-flex justify-center items-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm text-center">
                                                Add to Wishlist
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <!-- Admin View -->
                                @if($foodItem->stock_quantity > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        In Stock ({{ $foodItem->stock_quantity }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                @endif
                            @endunless
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Rating</h4>
                        @if ($reviewCount > 0)
                            <div class="flex items-center mt-1">
                                <span
                                    class="text-2xl font-bold text-gray-800 dark:text-gray-200 mr-2">{{ number_format($averageRating, 1) }}</span>
                                @for ($i = 1; $i <= round($averageRating); $i++)
                                    <svg class="w-5 h-5 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                @endfor
                                @for ($i = round($averageRating) + 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 fill-current"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">({{ $reviewCount }}
                                    {{ $reviewCount == 1 ? 'review' : 'reviews' }})</span>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">No reviews yet.</p>
                        @endif
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700">

                    @if($canReview)
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Leave a Review</h4>
                        <form method="POST" action="{{ route('reviews.store') }}">
                            @csrf
                            <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}">

                            <div>
                                <x-input-label for="rating" :value="__('Rating')" />
                                <select id="rating" name="rating"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required>
                                    <option value="" disabled selected>Select a rating</option>
                                    <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                    <option value="4">⭐⭐⭐⭐ (Good)</option>
                                    <option value="3">⭐⭐⭐ (Average)</option>
                                    <option value="2">⭐⭐ (Poor)</option>
                                    <option value="1">⭐ (Bad)</option>
                                </select>
                                <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="comment" :value="__('Comment (Optional)')" />
                                <textarea id="comment" name="comment" rows="4"
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('comment') }}</textarea>
                                <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="ms-4">
                                    {{ __('Submit Review') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @else
                        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-start space-x-3">
                            <svg class="w-6 h-6 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                @can('is-admin')
                                    Admins cannot leave reviews. Only students can review items they have ordered.
                                @else
                                    You can only review this item after ordering it and receiving your completed order.
                                @endcan
                            </p>
                        </div>
                    @endif
                </div>

                <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">All Reviews</h4>

                    @if ($reviews->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">No reviews yet. Be the first!</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($reviews as $review)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                    <div class="flex justify-between items-center">
                                        <h5 class="font-bold text-gray-900 dark:text-gray-100">
                                            {{ $review->user->name }}</h5>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $review->created_at->format('M d, Y') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center mt-1">
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            <svg class="w-5 h-5 text-yellow-400 fill-current"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                            </svg>
                                        @endfor
                                        @for ($i = $review->rating; $i < 5; $i++)
                                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 fill-current"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                            </svg>
                                        @endfor
                                    </div>

                                    @if ($review->comment)
                                        <p class="mt-3 text-gray-700 dark:text-gray-300">{{ $review->comment }}</p>
                                    @endif

                                    @can('is-admin')
                                        <form method="POST" action="{{ route('reviews.destroy', $review) }}"
                                            class="mt-2 text-right">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete
                                                Review</button>
                                        </form>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
