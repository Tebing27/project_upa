<?php

use App\Concerns\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Security settings')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $canManageTwoFactor;

    public bool $twoFactorEnabled;

    public bool $requiresConfirmation;

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();

        if ($this->canManageTwoFactor) {
            if (Fortify::confirmsTwoFactorAuthentication() && is_null(auth()->user()->two_factor_confirmed_at)) {
                $disableTwoFactorAuthentication(auth()->user());
            }

            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
            $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
        }
    }

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }

    /**
     * Handle the two-factor authentication enabled event.
     */
    #[On('two-factor-enabled')]
    public function onTwoFactorEnabled(): void
    {
        $this->twoFactorEnabled = true;
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication(auth()->user());

        $this->twoFactorEnabled = false;
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Security settings') }}</h2>

    <x-pages::settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <div x-data="{ show: false }">
                <label for="current_password" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Current password') }}</label>
                <div class="relative mt-2">
                    <input :type="show ? 'text' : 'password'" wire:model="current_password" id="current_password" required autocomplete="current-password" class="block w-full rounded-md border-0 py-1.5 pr-10 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.076m1.089-3.34A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-2.102-2.102L3 3" /></svg>
                    </button>
                </div>
                @error('current_password') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div x-data="{ show: false }">
                <label for="password" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('New password') }}</label>
                <div class="relative mt-2">
                    <input :type="show ? 'text' : 'password'" wire:model="password" id="password" required autocomplete="new-password" class="block w-full rounded-md border-0 py-1.5 pr-10 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.076m1.089-3.34A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-2.102-2.102L3 3" /></svg>
                    </button>
                </div>
                @error('password') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div x-data="{ show: false }">
                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Confirm password') }}</label>
                <div class="relative mt-2">
                    <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" id="password_confirmation" required autocomplete="new-password" class="block w-full rounded-md border-0 py-1.5 pr-10 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.076m1.089-3.34A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-2.102-2.102L3 3" /></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors" data-test="update-password-button">
                        {{ __('Save') }}
                    </button>
                </div>

                <x-action-message class="text-sm text-zinc-600 dark:text-zinc-400" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @if ($canManageTwoFactor)
            <section class="mt-12 pt-12 border-t border-zinc-200 dark:border-zinc-800">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('Two-factor authentication') }}</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Manage your two-factor authentication settings') }}</p>

                <div class="mt-6 flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
                    @if ($twoFactorEnabled)
                        <div class="space-y-4">
                            <p class="text-zinc-600 dark:text-zinc-400">
                                {{ __('You will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                            </p>

                            <div class="flex justify-start">
                                <button
                                    type="button"
                                    wire:click="disable"
                                    class="inline-flex items-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors"
                                >
                                    {{ __('Disable 2FA') }}
                                </button>
                            </div>

                            <livewire:pages::settings.two-factor.recovery-codes :$requiresConfirmation />
                        </div>
                    @else
                        <div class="space-y-4">
                            <p class="text-zinc-500 dark:text-zinc-400 italic">
                                {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                            </p>

                            <div x-data="{ open: false }" @close-modal.window="if ($event.detail === 'two-factor-setup-modal') open = false">
                                <button
                                    type="button"
                                    @click="open = true; $dispatch('start-two-factor-setup')"
                                    class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors"
                                >
                                    {{ __('Enable 2FA') }}
                                </button>

                                <template x-if="open">
                                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/50 backdrop-blur-sm">
                                        <div @click.away="open = false" class="bg-white dark:bg-zinc-900 rounded-xl shadow-xl w-full max-w-lg overflow-hidden border border-zinc-200 dark:border-zinc-800">
                                            <livewire:pages::settings.two-factor-setup-modal :requires-confirmation="$requiresConfirmation" />
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        @endif
    </x-pages::settings.layout>
</section>
