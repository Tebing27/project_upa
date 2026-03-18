<?php

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="p-6">
    <form method="POST" wire:submit="deleteUser" class="space-y-6">
        <div>
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('Are you sure you want to delete your account?') }}</h3>

            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>
        </div>

        <div x-data="{ show: false }">
            <label for="delete_password" class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white">{{ __('Password') }}</label>
            <div class="relative mt-2">
                <input :type="show ? 'text' : 'password'" wire:model="password" id="delete_password" required class="block w-full rounded-md border-0 py-1.5 pr-10 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 bg-white dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.076m1.089-3.34A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-2.102-2.102L3 3" /></svg>
                </button>
            </div>
            @error('password') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <button 
                type="button" 
                @click="$dispatch('close-modal', 'confirm-user-deletion')"
                class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:hover:bg-zinc-700 transition-colors"
            >
                {{ __('Cancel') }}
            </button>

            <button 
                type="submit" 
                class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors" 
                data-test="confirm-delete-user-button"
            >
                {{ __('Delete account') }}
            </button>
        </div>
    </form>
</div>
