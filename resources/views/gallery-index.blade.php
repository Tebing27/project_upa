<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Dokumentasi - LSP UPNVJ</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js is loaded for modal functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 flex flex-col min-h-screen" 
      x-data="{ 
          modalOpen: false, 
          modalImage: '', 
          modalTitle: '', 
          modalDesc: '' 
      }"
      @keydown.escape.window="modalOpen = false">
    
    <x-public.navbar />

    <main class="flex-grow pt-32 pb-24 px-6 lg:px-16 container mx-auto">
        <div class="max-w-[85rem] mx-auto w-full">
            <!-- Header section -->
            <div class="text-center mb-16">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-4 tracking-tight">Dokumentasi <span class="text-[#ea580c]">Kegiatan</span></h1>
                <p class="text-gray-600 max-w-2xl mx-auto mt-4 text-lg">Momen-momen penting dari perjalanan kegiatan sertifikasi di institusi kami.</p>
            </div>

            @if(isset($galleries) && $galleries->count() > 0)
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-12">
                    @foreach($galleries as $gallery)
                    @php $imgUrl = $gallery->image_path ? Storage::url($gallery->image_path) : 'https://placehold.co/800x600/e2e8f0/475569?text=Galeri'; @endphp
                    
                    <div class="group relative overflow-hidden rounded-xl bg-gray-900 aspect-[4/3] cursor-pointer"
                         @click="modalOpen = true; modalImage = '{{ $imgUrl }}'; modalTitle = '{{ addslashes($gallery->title) }}'; modalDesc = '{{ addslashes($gallery->description) }}';">
                        
                        <img src="{{ $imgUrl }}" alt="{{ $gallery->title }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 md:p-6 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <h3 class="mb-1 text-sm md:text-lg font-bold text-white leading-snug transform translate-y-4 transition-transform duration-300 group-hover:translate-y-0">{{ $gallery->title }}</h3>
                            @if($gallery->description)
                                <p class="text-xs md:text-sm text-gray-300 line-clamp-2 transform translate-y-4 transition-transform duration-300 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 delay-75">{{ $gallery->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $galleries->links() }}
                </div>
            @else
                <!-- Kosong -->
                <div class="text-center text-gray-500 py-16 bg-white rounded-2xl shadow-sm border border-gray-100 mt-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4 border border-gray-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="font-medium text-lg">Belum ada album atau dokumentasi saat ini.</p>
                </div>
            @endif
        </div>
    </main>

    <!-- Modal Lightbox (Alpine.js) -->
    <div x-show="modalOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center overflow-auto p-4 sm:p-8 bg-black/90 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
         
        <!-- Tutup dengan klik backdrop -->
        <div class="absolute inset-0 cursor-pointer" @click="modalOpen = false"></div>
        
        <!-- Konten Gambar -->
        <div class="relative max-w-5xl w-full mx-auto flex flex-col justify-center items-center pointer-events-none"
             x-transition:enter="transition ease-out duration-300 delay-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <button @click="modalOpen = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 pointer-events-auto transition rounded-full p-2 bg-black/40 hover:bg-black/80">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <img :src="modalImage" :alt="modalTitle" class="max-h-[75vh] w-auto max-w-full rounded-lg shadow-2xl bg-gray-900 mx-auto pointer-events-auto border border-gray-700">
            
            <!-- Modal Text -->
            <div class="bg-black/60 backdrop-blur-md rounded-b-lg w-full max-w-[var(--tw-max-w)] max-h-min border border-t-0 border-gray-800 p-6 pointer-events-auto mt-4 text-center">
                <h3 class="text-xl md:text-2xl font-bold text-white mb-2" x-text="modalTitle"></h3>
                <p class="text-gray-300 text-sm md:text-base max-w-3xl mx-auto" x-text="modalDesc"></p>
            </div>
            
        </div>
    </div>

    <x-public.footer />

</body>
</html>
