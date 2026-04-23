@props(['article'])

<div
    {{ $attributes->class('group flex flex-col overflow-hidden rounded-[1.25rem] border border-gray-50 bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] transition-all hover:shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)]') }}>
    <a href="{{ $article->url }}" class="relative block aspect-video w-full overflow-hidden bg-gray-200">
        @if ($article->image_path)
            <img src="{{ $article->image_path }}" alt="{{ $article->title }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <img src="https://placehold.co/600x338/f1f5f9/64748b?text=News+Update" alt="News Placeholder"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @endif
    </a>

    <div class="flex flex-1 flex-col p-6 lg:p-8">
        <div class="flex-1">
            <div class="mb-4 flex items-center justify-between gap-3 text-xs font-semibold text-gray-500">
                <div class="flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-[#ea580c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ \Carbon\Carbon::parse($article->published_at ?? $article->created_at)->format('d M Y') }}
                </div>
                <div class="flex items-center gap-1.5" title="Dilihat {{ $article->views_count }} kali">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ $article->views_count }}
                </div>
            </div>

            <x-public.article-tags :tags="$article->tags" class="mb-3" />

            <a href="{{ $article->url }}" class="block">
                <h3
                    class="mb-3 line-clamp-2 text-xl leading-snug font-bold text-gray-900 transition-colors group-hover:text-[#ea580c]">
                    {{ $article->title }}
                </h3>
            </a>

            <p class="mb-4 line-clamp-3 text-sm leading-relaxed text-gray-600">
                {{ $article->excerpt }}
            </p>
        </div>

        <div class="mt-auto flex items-center justify-start border-t border-gray-100 pt-5">
            <a href="{{ $article->url }}"
                class="flex items-center gap-1 text-sm font-bold text-[#ea580c] transition-colors hover:text-[#c2410c]">
                Baca Selengkapnya
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</div>
