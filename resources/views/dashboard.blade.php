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
