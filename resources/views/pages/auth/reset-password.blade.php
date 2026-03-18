<x-layouts.auth :title="__('Reset password')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Email') }}</label>
                <div class="mt-2">
                    <input type="email" name="email" id="email" value="{{ request('email') }}" required autocomplete="email" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Password') }}</label>
                <div class="mt-2">
                    <input type="password" name="password" id="password" required autocomplete="new-password" placeholder="{{ __('Password') }}" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6 placeholder:text-zinc-400">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Confirm password') }}</label>
                <div class="mt-2">
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm password') }}" class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6 placeholder:text-zinc-400">
                </div>
                @error('password_confirmation')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="w-full justify-center inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors" data-test="reset-password-button">
                    {{ __('Reset password') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.auth>
