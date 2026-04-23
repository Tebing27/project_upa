@props(['gallery', 'interactive' => false])

@php
    $imageUrl = $gallery->file_path ? Storage::url($gallery->file_path) : 'https://placehold.co/800x600/e2e8f0/475569?text=Galeri';
@endphp

<div
    {{ $attributes->class('group relative aspect-[4/3] overflow-hidden rounded-xl bg-gray-900') }}
    @if ($interactive)
        @click="modalOpen = true; modalImage = '{{ $imageUrl }}'; modalTitle = '{{ addslashes($gallery->title) }}'; modalDesc = '{{ addslashes($gallery->description ?? '') }}'; modalType = '{{ $gallery->type }}';"
    @endif
>
    @if ($gallery->type === 'video')
        <video src="{{ $imageUrl }}"
            class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
            muted loop onmouseover="this.play()" onmouseout="this.pause()"></video>
    @else
        <img src="{{ $imageUrl }}" alt="{{ $gallery->title }}"
            class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
    @endif

    <div
        class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100 md:p-6">
        <h3
            class="mb-1 translate-y-4 text-sm leading-snug font-bold text-white transition-transform duration-300 group-hover:translate-y-0 md:text-lg">
            {{ $gallery->title }}
        </h3>
        @if ($gallery->description)
            <p
                class="line-clamp-2 translate-y-4 text-xs text-gray-300 opacity-0 transition-all delay-75 duration-300 group-hover:translate-y-0 group-hover:opacity-100 md:text-sm">
                {{ $gallery->description }}
            </p>
        @endif
    </div>
</div>
