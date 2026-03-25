<div x-data="{ open: false }" class="relative w-full">
    <button @click="open = !open" @click.outside="open = false"
        class="flex w-full items-center justify-between gap-2 p-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-800/50 rounded-md transition-colors"
        data-test="sidebar-menu-button">
        <div class="flex items-center gap-3 overflow-hidden">
            <div
                class="flex size-9 shrink-0 items-center justify-center rounded-md bg-zinc-200 dark:bg-zinc-700 text-sm font-semibold uppercase text-zinc-700 dark:text-zinc-300">
                {{ auth()->user()->initials() }}
            </div>
            <div class="grid flex-1 text-sm leading-tight lg:hidden xl:grid">
                <div class="truncate font-medium text-zinc-900 dark:text-white">{{ auth()->user()->name }}</div>
            </div>
        </div>
        <svg class="size-4 shrink-0 text-zinc-500 lg:hidden xl:block" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
        </svg>
    </button>

    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;"
        class="absolute bottom-full left-0 mb-2 w-64 rounded-xl border border-zinc-200 bg-white p-1 shadow-lg dark:border-zinc-700 dark:bg-zinc-900 z-50">
        <div class="px-2 py-2 text-sm">
            <div class="font-medium text-zinc-900 dark:text-white truncate">{{ auth()->user()->name }}</div>
            <div class="text-zinc-500 dark:text-zinc-400 truncate">{{ auth()->user()->email }}</div>
        </div>
        <div class="my-1 h-px bg-zinc-200 dark:bg-zinc-800"></div>
        <a href="{{ route('profile.edit') }}" wire:navigate
            class="flex items-center gap-2 rounded-md px-2 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800/50 text-zinc-700 dark:text-zinc-300">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ __('Settings') }}
        </a>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-sm text-left hover:bg-zinc-100 dark:hover:bg-zinc-800/50 text-zinc-700 dark:text-zinc-300"
                data-test="logout-button">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</div>
