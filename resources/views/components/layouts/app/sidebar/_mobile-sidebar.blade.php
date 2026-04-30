<div x-show="sidebarOpen" style="display: none;" class="fixed inset-0 z-40 bg-black/50 lg:hidden"
    @click="sidebarOpen = false" x-transition.opacity></div>

<div x-show="sidebarOpen" style="display: none;"
    class="fixed inset-y-0 left-0 z-50 w-64 transform flex-col bg-white dark:bg-zinc-900 lg:hidden"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
    <div class="flex h-14 items-center justify-between border-b border-zinc-200 px-4 dark:border-zinc-800">
        <x-app-logo href="{{ $homeRoute }}" wire:navigate />
        <button @click="sidebarOpen = false"
            class="text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-6">
        @include('components.layouts.app.sidebar._navigation')
    </nav>
</div>
