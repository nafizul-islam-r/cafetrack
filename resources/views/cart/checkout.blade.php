<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Order Summary -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Order Summary</h3>
                    <div class="space-y-4">
                        @php $subtotal = 0; @endphp
                        @foreach($cart as $id => $details)
                            @php $subtotal += $details['price'] * $details['quantity']; @endphp
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-4">
                                    <span class="font-medium">{{ $details['quantity'] }}x</span>
                                    <span>{{ $details['name'] }}</span>
                                </div>
                                <span>BDT {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 mt-6 pt-4 flex justify-between items-center font-bold text-lg">
                        <span>Total</span>
                        <span>BDT {{ number_format($subtotal, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Payment & Order Details</h3>
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        
                        <!-- Order Type -->
                        <div class="mb-6" x-data="{ orderType: 'dine_in' }">
                            <span class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Order Type</span>
                            <div class="space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-indigo-600" name="order_type" value="dine_in" x-model="orderType">
                                    <span class="ml-2">Dine-in</span>
                                </label>
                                <br>
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-indigo-600" name="order_type" value="takeaway" x-model="orderType">
                                    <span class="ml-2">Takeaway</span>
                                </label>
                            </div>
                            
                            <div x-show="orderType === 'takeaway'" class="mt-3 text-sm text-yellow-600 bg-yellow-50 p-2 rounded border border-yellow-200">
                                <strong>Notice:</strong> Takeaway orders must be picked up within 30 minutes of placement, or they will be automatically cancelled.
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <span class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Payment Method</span>
                            <div class="space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-indigo-600" name="payment_method" value="cash" checked>
                                    <span class="ml-2">Cash (Pay at counter)</span>
                                </label>
                                <br>
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-indigo-600" name="payment_method" value="bkash">
                                    <span class="ml-2">bKash (Online)</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
