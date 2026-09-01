<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Order {{ $order->order_number }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                &larr; Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Main Content: Order Items -->
            <div class="md:col-span-2 space-y-6">
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Items Ordered</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $item['image_url'] ?? 'https://placehold.co/100x100?text=Food' }}" alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['name'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $item['quantity'] }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                BDT {{ number_format($item['price'], 2) }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                BDT {{ number_format($item['price'] * $item['quantity'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between items-center text-lg font-bold">
                            <span>Order Total</span>
                            <span>BDT {{ number_format($order->total, 2) }}</span>
                        </div>
                </div>

                @if($order->order_type === 'takeaway' && $order->order_status === 'pending')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6" x-data="takeawayTimer('{{ $order->created_at->addMinutes(30)->toISOString() }}')">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">
                                    Takeaway Time Limit
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Please pick up your order within 30 minutes of placement. Your order will be automatically cancelled if not collected.</p>
                                    <p class="font-bold mt-1 text-lg">Time remaining: <span x-text="timeRemaining"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function takeawayTimer(expirationTime) {
                            return {
                                timeRemaining: 'Calculating...',
                                init() {
                                    const updateTimer = () => {
                                        const now = new Date().getTime();
                                        const exp = new Date(expirationTime).getTime();
                                        const distance = exp - now;

                                        if (distance < 0) {
                                            this.timeRemaining = "EXPIRED";
                                            return;
                                        }

                                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        this.timeRemaining = minutes + "m " + seconds + "s ";
                                    };
                                    
                                    updateTimer();
                                    setInterval(updateTimer, 1000);
                                }
                            }
                        }
                    </script>
                @endif

            </div>

            <!-- Sidebar: Details & Actions -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4 border-b pb-2">Status</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Order Status:</span>
                                <span class="font-medium inline-flex items-center px-2 py-0.5 rounded text-xs
                                    @if($order->order_status == 'completed') bg-green-100 text-green-800
                                    @elseif($order->order_status == 'pending') bg-blue-100 text-blue-800
                                    @elseif($order->order_status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-500">Payment:</span>
                                <span class="font-medium inline-flex items-center px-2 py-0.5 rounded text-xs {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($order->payment_status) }} ({{ ucfirst($order->payment_method) }})
                                </span>
                            </div>

                            @if($order->token_number)
                                <div class="flex justify-between items-center bg-blue-50 dark:bg-blue-900 p-3 rounded-md mt-4 border border-blue-200 dark:border-blue-700">
                                    <span class="text-blue-700 dark:text-blue-300 font-medium">Token Number:</span>
                                    <span class="text-xl font-bold text-blue-800 dark:text-blue-100">T-{{ str_pad($order->token_number, 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4 border-b pb-2">Customer Details</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium text-gray-500">Name:</span> {{ $order->user_name }}</p>
                            <p><span class="font-medium text-gray-500">Student ID:</span> {{ $order->user_student_id }}</p>
                            <p><span class="font-medium text-gray-500">Email:</span> {{ $order->user_email }}</p>
                            <p><span class="font-medium text-gray-500">Order Type:</span> {{ ucfirst(str_replace('_', '-', $order->order_type)) }}</p>
                            <p><span class="font-medium text-gray-500">Placed At:</span> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Actions -->
                @if(Gate::allows('is-admin'))
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-2 border-indigo-200 dark:border-indigo-900">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium mb-4 text-indigo-800 dark:text-indigo-300">Admin Actions</h3>
                            <div class="space-y-3">
                                @if($order->order_status !== 'cancelled' && $order->order_status !== 'completed')
                                    
                                    @if($order->payment_status === 'unpaid')
                                        <form action="{{ route('orders.mark-paid', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none transition">
                                                Mark as Paid
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->payment_status === 'paid')
                                        <form action="{{ route('orders.mark-completed', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none transition">
                                                Mark as Completed
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order? Stock will be restored if it was already paid.');">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none transition">
                                            Cancel Order
                                        </button>
                                    </form>

                                @else
                                    <p class="text-sm text-gray-500">No actions available. Order is {{ $order->order_status }}.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
