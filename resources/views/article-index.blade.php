<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Berita & Pengumuman - LSP UPNVJ</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 flex flex-col min-h-screen">
    
    <x-public.navbar />

    <main class="flex-grow pt-32 pb-24 px-6 lg:px-16 container mx-auto">
        <div class="max-w-[85rem] mx-auto w-full">
            <div class="text-center mb-16">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-4 tracking-tight">Semua Berita & <span class="text-[#ea580c]">Pengumuman</span></h1>
                <p class="text-gray-600 max-w-2xl mx-auto mt-4 text-lg">Telusuri berbagai informasi akademik, pembaruan jadwal operasional, serta tips kelulusan sertifikasi langsung dari ruang pengurus LSP UPN Veteran Jakarta.</p>
            </div>

            @if(isset($articles) && $articles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($articles as $article)
                        <div class="group flex flex-col overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] transition-all hover:shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-gray-50">
                            <!-- Thumbnail 16:9 -->
                            <a href="{{ route('article.show', $article->slug) }}" class="relative w-full aspect-video overflow-hidden bg-gray-200 block">
                                @if($article->image_path)
                                    <img src="{{ Storage::url($article->image_path) }}" alt="{{ $article->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <img src="https://placehold.co/600x338/f1f5f9/64748b?text=News+Update" alt="News Placeholder" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @endif
                            </a>
                            
                            <!-- Content -->
                            <div class="flex flex-1 flex-col p-6 lg:p-8">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between gap-3 mb-4 text-xs font-semibold text-gray-500">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-[#ea580c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}
                                        </div>
                                        <div class="flex items-center gap-1.5" title="Dilihat {{ $article->views_count }} kali">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            {{ $article->views_count }}
                                        </div>
                                    </div>
                                    
                                    @if($article->tags && is_array($article->tags) && count($article->tags) > 0)
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            @foreach($article->tags as $tag)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <a href="{{ route('article.show', $article->slug) }}" class="block">
                                        <h3 class="text-xl font-bold text-gray-900 line-clamp-2 mb-3 leading-snug group-hover:text-[#ea580c] transition-colors">
                                            {{ $article->title }}
                                        </h3>
                                    </a>
                                    
                                    <p class="text-sm text-gray-600 line-clamp-3 mb-4 leading-relaxed">
                                        {{ $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->body), 100) }}
                                    </p>
                                </div>
                                
                                <div class="mt-auto pt-5 border-t border-gray-100 flex justify-start items-center">
                                    <a href="{{ route('article.show', $article->slug) }}" class="text-sm font-bold text-[#ea580c] hover:text-[#c2410c] transition-colors flex items-center gap-1">
                                        Baca Selengkapnya
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8 flex justify-center">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="text-center text-gray-500 py-16 bg-white rounded-2xl shadow-sm border border-gray-100 mt-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4 border border-gray-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <p class="font-medium text-lg">Belum ada berita atau pengumuman saat ini.</p>
                </div>
            @endif
        </div>
    </main>

    <x-public.footer />

</body>
</html>
