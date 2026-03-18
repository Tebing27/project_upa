<x-layouts.auth :title="__('Confirm password')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Password') }}</label>
                <div class="mt-2">
                    <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="{{ __('Password') }}" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6 placeholder:text-zinc-400">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full justify-center inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors" data-test="confirm-password-button">
                {{ __('Confirm') }}
            </button>
        </form>
    </div>
</x-layouts.auth>
