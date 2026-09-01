<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ Gate::allows('is-admin') ? __('Manage Orders') : __('My Orders') }}
            </h2>
            @if(Gate::allows('is-admin'))
                <div class="flex space-x-2">
                    <a href="{{ route('orders.index') }}" class="px-3 py-1 rounded-md text-sm {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">All</a>
                    <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'pending' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">Pending</a>
                    <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'completed' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">Completed</a>
                    <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" class="px-3 py-1 rounded-md text-sm {{ request('status') == 'cancelled' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">Cancelled</a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @php
                        $pendingTakeaways = $orders->filter(function($order) {
                            return $order->order_status === 'pending' && $order->order_type === 'takeaway';
                        });
                    @endphp

                    @if($pendingTakeaways->isNotEmpty())
                        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-20 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                        You have {{ $pendingTakeaways->count() }} takeaway order(s) awaiting pickup!
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach($pendingTakeaways as $pto)
                                                @php
                                                    $expiresIn = max(0, 30 - $pto->created_at->diffInMinutes(now()));
                                                @endphp
                                                <li>
                                                    <a href="{{ route('orders.show', $pto) }}" class="font-bold underline hover:text-yellow-900 dark:hover:text-yellow-100">
                                                        {{ $pto->order_number }}
                                                    </a> 
                                                    - Expires in {{ $expiresIn }} minute(s)
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(Gate::allows('is-admin'))
                        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                            <form method="GET" action="{{ route('orders.index') }}" class="flex flex-wrap gap-2 items-center">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <input type="text" name="order_number" value="{{ request('order_number') }}" placeholder="Order #" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm w-32 sm:w-auto">
                                <input type="text" name="token" value="{{ request('token') }}" placeholder="Token #" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm w-32 sm:w-auto">
                                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">Search</button>
                                @if(request('token') || request('order_number'))
                                    <a href="{{ route('orders.index', request()->only('status')) }}" class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">Clear</a>
                                @endif
                            </form>
                            
                            <a href="{{ route('orders.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-700 transition">
                                + Create Manual Order
                            </a>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($orders->isEmpty())
                        <p class="text-gray-500">No orders found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order #</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        @if(Gate::allows('is-admin'))
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                        @endif
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $order->order_number }}
                                                @if($order->token_number)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        Token: T-{{ str_pad($order->token_number, 3, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $order->created_at->format('M d, Y h:i A') }}
                                            </td>
                                            @if(Gate::allows('is-admin'))
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    {{ $order->user_name }}<br>
                                                    <span class="text-xs text-gray-400">{{ $order->user_student_id }}</span>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                BDT {{ number_format($order->total, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($order->payment_status) }} ({{ ucfirst($order->payment_method) }})
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    @if($order->order_status == 'completed') bg-green-100 text-green-800
                                                    @elseif($order->order_status == 'pending') bg-blue-100 text-blue-800
                                                    @elseif($order->order_status == 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif
                                                ">
                                                    {{ ucfirst($order->order_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
