@props([
    'sidebar' => false,
])

@php
    $class = $sidebar
        ? "flex items-center gap-2 text-lg font-semibold tracking-tight text-zinc-900 dark:text-white"
        : "flex items-center gap-2 text-lg font-semibold tracking-tight text-zinc-900 dark:text-white";
    $logoClass = $sidebar
        ? 'size-8 rounded-md object-contain'
        : 'size-8 rounded-md object-contain';
@endphp

<a {{ $attributes->merge(['class' => $class]) }}>
    <img src="{{ asset('assets/logo.webp') }}" alt="Logo UPA LUK" class="{{ $logoClass }}">
    <span class="truncate">UPA LUK</span>
</a>
