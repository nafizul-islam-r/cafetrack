<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Welcome back, ") . Auth::user()->name . "!" }}
                </div>
            </div>

            @if(isset($pendingTakeaways) && $pendingTakeaways->isNotEmpty())
                <div class="bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-20 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
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
                                            $expiresIn = max(0, 30 - (int) $pto->created_at->diffInMinutes(now()));
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if(Gate::allows('is-admin'))
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pending Orders</h3>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $stats['pending_orders'] }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Completed Orders</h3>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['completed_orders'] }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Total Orders</h3>
                        <p class="text-3xl font-bold text-gray-600 mt-2">{{ $stats['total_orders'] }}</p>
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">My Pending Orders</h3>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $stats['my_pending'] }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">My Total Orders</h3>
                        <p class="text-3xl font-bold text-gray-600 mt-2">{{ $stats['my_orders'] }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
