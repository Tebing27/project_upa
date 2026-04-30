    <div
        class="flex h-14 items-center justify-between border-b border-zinc-200 bg-white px-4 dark:border-zinc-800 dark:bg-zinc-900 lg:hidden">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true"
                class="text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <x-app-logo href="{{ $homeRoute }}" wire:navigate />
        </div>

        <x-desktop-user-menu />
    </div>
