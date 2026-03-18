<?php

use Livewire\Component;

new class extends Component {}; ?>

<section class="mt-10 pt-10 border-t border-zinc-200 dark:border-zinc-800 space-y-6">
    <div class="relative mb-5">
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ __('Delete account') }}</h3>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Delete your account and all of its resources') }}</p>
    </div>

    <div x-data="{ open: false }" @close-modal.window="if ($event.detail === 'confirm-user-deletion') open = false">
        <button 
            type="button"
            @click="open = true"
            class="inline-flex items-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-colors" 
            data-test="delete-user-button"
        >
            {{ __('Delete account') }}
        </button>

        <template x-if="open">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/50 backdrop-blur-sm">
                <div @click.away="open = false" class="bg-white dark:bg-zinc-900 rounded-xl shadow-xl w-full max-w-lg overflow-hidden border border-zinc-200 dark:border-zinc-800">
                    <livewire:pages::settings.delete-user-modal />
                </div>
            </div>
        </template>
    </div>
</section>
