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

                    @if(Gate::allows('is-admin'))
                        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                            <form method="GET" action="{{ route('orders.index') }}" class="flex items-center space-x-2">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <input type="text" name="token" value="{{ request('token') }}" placeholder="Search Token or Order #" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm">
                                <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">Search</button>
                                @if(request('token'))
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
