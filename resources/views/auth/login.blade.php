<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Phone Number -->
        <div>
            <x-input-label class="block mt-1 w-full p-2" for="phone" :value="__('Phone Number')" />
            <input id="phone" class=" block mt-1 w-full p-2 border-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white" type="phone" name="phone" value="{{ old('phone') }}" required autofocus autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-2 border-gray-800 dark:border-gray-400 bg-gray-100 dark:bg-gray-800 text-indigo-600 shadow-md focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 w-5 h-5" name="remember">
            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">


            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
