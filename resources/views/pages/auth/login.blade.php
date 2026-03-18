<x-layouts.auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your NIM and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- NIM -->
            <div>
                <label for="nim" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('NIM') }}</label>
                <div class="mt-2">
                    <input type="text" name="nim" id="nim" value="{{ old('nim') }}" autocomplete="username" placeholder="123456789" required autofocus class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6 placeholder:text-zinc-400">
                </div>
                @error('nim')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="relative">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Password') }}</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-zinc-900 hover:underline dark:text-white" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
                <div class="mt-2">
                    <input type="password" name="password" id="password" autocomplete="current-password" placeholder="{{ __('Password') }}" required class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6 placeholder:text-zinc-400">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center gap-2">
                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-700 text-zinc-900 focus:ring-zinc-900 dark:bg-zinc-800 dark:checked:bg-white dark:checked:text-zinc-900" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="block text-sm text-zinc-900 dark:text-white">{{ __('Remember me') }}</label>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="w-full justify-center inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors" data-test="login-button">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <a href="{{ route('register') }}" class="font-medium text-zinc-900 hover:underline dark:text-white" wire:navigate>{{ __('Sign up') }}</a>
            </div>
        @endif
    </div>
</x-layouts.auth>
