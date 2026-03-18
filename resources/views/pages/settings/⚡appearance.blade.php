<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Appearance settings')] class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Appearance settings') }}</h2>

    <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <div x-data="{ appearance: $flux.appearance }" class="mt-6 flex flex-col space-y-4">
            <div class="inline-flex p-1 bg-zinc-100 dark:bg-zinc-800 rounded-lg">
                <button 
                    @click="appearance = 'light'; $flux.appearance = 'light'" 
                    :class="appearance === 'light' ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200'"
                    class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-md transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>
                    {{ __('Light') }}
                </button>
                <button 
                    @click="appearance = 'dark'; $flux.appearance = 'dark'" 
                    :class="appearance === 'dark' ? 'bg-white text-zinc-900 dark:bg-zinc-700 dark:text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200'"
                    class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-md transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    {{ __('Dark') }}
                </button>
                <button 
                    @click="appearance = 'system'; $flux.appearance = 'system'" 
                    :class="appearance === 'system' ? 'bg-white text-zinc-900 dark:bg-zinc-700 dark:text-white shadow-sm' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200'"
                    class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-md transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    {{ __('System') }}
                </button>
            </div>
        </div>
    </x-pages::settings.layout>
</section>
