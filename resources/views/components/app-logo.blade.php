@props([
    'sidebar' => false,
])

@php
    $class = $sidebar 
        ? "flex items-center gap-2 text-lg font-semibold tracking-tight text-zinc-900 dark:text-white"
        : "flex items-center gap-2 text-lg font-semibold tracking-tight text-zinc-900 dark:text-white";
@endphp

<a {{ $attributes->merge(['class' => $class]) }}>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
        <x-app-logo-icon class="size-5 fill-current" />
    </div>
    <span class="truncate">{{ config('app.name', 'Laravel') }}</span>
</a>
