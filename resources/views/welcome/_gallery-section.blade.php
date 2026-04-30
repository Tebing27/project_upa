    <!-- Galeri Section -->
    <div class="bg-white py-24 relative z-10 border-t border-gray-100">
        <div class="max-w-[85rem] mx-auto w-full relative z-10 px-4 sm:px-6 md:px-12">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">Galeri Kegiatan <span
                        class="text-[#ea580c]">UPA LUK</span></h2>
                <p class="text-gray-600 mt-4 text-lg">Dokumentasi kegiatan dan uji kompetensi di lingkungan Universitas
                    Pembangunan Nasional Veteran Jakarta.</p>
            </div>

            @if (isset($galleries) && $galleries->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4">
                    @foreach ($galleries as $gallery)
                        <x-public.gallery-card :gallery="$gallery" />
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="group relative aspect-[4/3] overflow-hidden rounded-xl bg-gray-100">
                            <img src="https://placehold.co/800x600/e2e8f0/475569?text=Galeri+{{ $i }}"
                                alt="Galeri Dummy {{ $i }}"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div
                                class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100 md:p-6">
                                <h3 class="text-sm font-bold text-white md:text-lg">Kegiatan LSP {{ $i }}
                                </h3>
                            </div>
                        </div>
                    @endfor
                </div>
            @endif

            <div class="mt-12 text-center">
                <a href="{{ route('gallery.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 hover:text-[#ea580c] hover:border-[#ea580c] transition-colors gap-2">
                    Lihat Semua Dokumentasi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

