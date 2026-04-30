<div
    class="fixed inset-y-0 left-0 z-50 hidden w-64 flex-col border-r border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 lg:flex">
    <div class="flex h-14 items-center border-b border-zinc-200 px-4 dark:border-zinc-800">
        <x-app-logo :sidebar="true" href="{{ $homeRoute }}" wire:navigate />
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-6">
        @include('components.layouts.app.sidebar._navigation')
    </nav>

    <div class="mt-auto border-t border-zinc-200 p-4 dark:border-zinc-800">
        <x-desktop-user-menu />
    </div>
</div>
