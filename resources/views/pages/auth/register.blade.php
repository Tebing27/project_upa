<x-layouts.auth.wide :title="__('Register')">
    <div class="flex flex-col gap-10">
        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <livewire:auth.registration-stepper />

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-500 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <a href="{{ route('login') }}" class="font-bold text-zinc-900 hover:underline dark:text-white"
                wire:navigate>{{ __('Log in') }}</a>
        </div>
    </div>
</x-layouts.auth.wide>
