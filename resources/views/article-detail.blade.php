<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} - LSP UPNVJ</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style> 
        body { font-family: 'Inter', sans-serif; } 
        /* Basic fallback css for rich-text if tailwind typography isn't installed */
        .fallback-prose p { margin-bottom: 1.25em; line-height: 1.75; }
        .fallback-prose h1, .fallback-prose h2, .fallback-prose h3 { font-weight: 700; margin-top: 1.5em; margin-bottom: 0.5em; }
        .fallback-prose h2 { font-size: 1.5em; }
        .fallback-prose h3 { font-size: 1.25em; }
        .fallback-prose ul { list-style-type: disc; margin-left: 1.5em; margin-bottom: 1.25em; }
        .fallback-prose ol { list-style-type: decimal; margin-left: 1.5em; margin-bottom: 1.25em; }
        .fallback-prose a { color: #ea580c; text-decoration: underline; }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 flex flex-col min-h-screen">
    
    <x-public.navbar />

    <main class="flex-grow pt-32 pb-24 px-6 container mx-auto">
        <div class="max-w-4xl mx-auto w-full bg-white p-8 md:p-12 rounded-[1.25rem] shadow-sm border border-gray-100">
            <!-- Back Button -->
            <a href="{{ route('article.index') }}" class="inline-flex items-center text-[#ea580c] hover:text-[#c2410c] font-semibold text-sm mb-8 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Berita
            </a>

            <!-- Header -->
            <header class="mb-8">
                @if($article->tags && is_array($article->tags) && count($article->tags) > 0)
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($article->tags as $tag)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                    {{ $article->title }}
                </h1>
                
                <!-- Meta data -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 font-medium">
                    <div class="flex items-center gap-1.5 border-r border-gray-200 pr-4">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ optional($article->user)->nama ?? 'Admin LSP' }}
                    </div>
                    <div class="flex items-center gap-1.5 border-r border-gray-200 pr-4">
                        <svg class="w-4 h-4 text-[#ea580c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $article->published_at ? $article->published_at->format('d F Y') : $article->created_at->format('d F Y') }}
                    </div>
                    <div class="flex items-center gap-1.5" title="Dilihat {{ $article->views_count }} kali">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        {{ $article->views_count }} Views
                    </div>
                </div>
            </header>

            <!-- Image Main -->
            <div class="relative w-full aspect-video overflow-hidden rounded-2xl bg-gray-200 mb-10">
                @if($article->image_path)
                    <img src="{{ Storage::url($article->image_path) }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
                @else
                    <img src="https://placehold.co/1200x675/f1f5f9/64748b?text=Visual+Berita" alt="News Placeholder" class="h-full w-full object-cover">
                @endif
            </div>

            <!-- Body -->
            <article class="prose prose-lg prose-slate max-w-none fallback-prose text-gray-700">
                {!! $article->body !!}
            </article>

        </div>
    </main>

    <x-public.footer />

</body>
</html>
