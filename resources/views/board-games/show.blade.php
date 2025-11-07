<x-app-layout>
    <x-slot name="header">
        <!-- We use items-baseline to align the title and the buttons on the same text line -->
        <div class="flex justify-between items-baseline">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Manage: {{ $boardGame->name }}
            </h2>

            @can('is-admin')
                <div class="flex items-baseline space-x-4">
                    <a href="{{ route('board-games.edit', $boardGame) }}"
                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-100 text-sm font-medium">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('board-games.destroy', $boardGame) }}"
                        onsubmit="return confirm('Are you sure you want to delete this game?');" class="m-0">
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
                    class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div
                    class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div
                    class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 rounded shadow-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Left Column: Game Details & Assign Form -->
                <div class="md:col-span-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <img src="{{ asset('storage/' . $boardGame->image_url) }}" alt="{{ $boardGame->name }}"
                        class="w-full h-64 object-cover rounded-lg mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $boardGame->name }}</h3>
                    <p
                        class="text-lg font-semibold mt-2 {{ $boardGame->available_units > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        Available: {{ $boardGame->available_units }} / {{ $boardGame->total_units }}
                    </p>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    <!-- Assign Form -->
                    <h4 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Assign Game</h4>
                    <form method="POST" action="{{ route('assignments.store') }}">
                        @csrf
                        <input type="hidden" name="board_game_id" value="{{ $boardGame->id }}">

                        <div>
                            <x-input-label for="student_id" :value="__('Student ID')" />
                            <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id"
                                :value="old('student_id')" required />
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4" :disabled="$boardGame->available_units <= 0">
                                {{ __('Assign') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Current Assignments -->
                <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h4 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Current Assignments</h4>

                    @if ($assignments->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">No units are currently assigned.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            User</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Student ID</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Assigned At</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($assignments as $assignment)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $assignment->user->name }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $assignment->user->student_id }}</td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $assignment->created_at->format('M d, Y - h:i A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <!-- Return Button -->
                                                <form method="POST"
                                                    action="{{ route('assignments.destroy', $assignment) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Return</button>
                                                </form>
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
