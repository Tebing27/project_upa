<div class="p-6">
    <div class="space-y-6">
        <div class="flex flex-col items-center space-y-4">
            <div class="p-0.5 w-auto rounded-full border border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-800 shadow-sm">
                <div class="p-2.5 rounded-full border border-zinc-200 dark:border-zinc-700 overflow-hidden bg-zinc-100 dark:bg-zinc-800 relative">
                    <svg class="w-8 h-8 text-zinc-600 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h2M4 8h2m12 0h2M4 6h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" /></svg>
                </div>
            </div>

            <div class="space-y-2 text-center">
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $this->modalConfig['title'] }}</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $this->modalConfig['description'] }}</p>
            </div>
        </div>
