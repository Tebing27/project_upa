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
                        <x-public.article-card :article="$article" />
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
