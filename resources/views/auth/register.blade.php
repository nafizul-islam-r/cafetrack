<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Department -->
        <div class="mt-4">
            <x-input-label for="department" :value="__('Department')" />

            <select id="department" name="department"
                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                required>

                <option value="" disabled selected class="dark:bg-gray-900 dark:text-gray-500">Select your
                    department</option>

                <option class="dark:bg-gray-900 dark:text-gray-300" value="CSE">CSE</option>
                <option class="dark:bg-gray-900 dark:text-gray-300" value="BBA">BBA</option>
                <option class="dark:bg-gray-900 dark:text-gray-300" value="EEE">EEE</option>
                <option class="dark:bg-gray-900 dark:text-gray-300" value="Textile">Textile</option>
                <option class="dark:bg-gray-900 dark:text-gray-300" value="CE">CE</option>
                <option class="dark:bg-gray-900 dark:text-gray-300" value="English">English</option>
                <option class="dark:bg-gray-900 dark:text-gray-300" value="Economics">Economics</option>
                <option class="dark:bg-gray-900 dark:text-gray-300" value="LL.B">LL.B</option>
            </select>

            <x-input-error :messages="$errors->get('department')" class="mt-2" />
        </div>

        <!-- Intake -->
        <div class="mt-4">
            <x-input-label for="intake" :value="__('Intake (e.g., 49)')" />
            <x-text-input id="intake" class="block mt-1 w-full" type="text" name="intake" :value="old('intake')"
                required />
            <x-input-error :messages="$errors->get('intake')" class="mt-2" />
        </div>

        <!-- Student ID -->
        <div class="mt-4">
            <x-input-label for="student_id" :value="__('Student ID')" />
            <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id')"
                required />
            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
