    <!-- Berita & Pengumuman Section -->
    <div class="bg-gray-50 py-24 relative z-10 border-t border-gray-100" x-data="{
        scroll(direction) {
            const container = this.$refs.beritaContainer;
            const scrollAmount = container.clientWidth * 0.8;
            container.scrollBy({
                left: direction === 'left' ? -scrollAmount : scrollAmount,
                behavior: 'smooth'
            });
        }
    }">
        <div class="relative mx-auto w-full max-w-[85rem] px-4 sm:px-6 md:px-12">
            <div class="mb-12 flex flex-col gap-6 lg:mb-16 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-black leading-tight text-gray-950 sm:text-4xl lg:text-[2.75rem]">Berita &
                        <span class="text-[#ea580c]">Pengumuman Terbaru</span>
                    </h2>
                    <p class="text-gray-600 mt-4 text-base sm:text-lg">Informasi dan pembaruan terkini seputar kegiatan
                        sertifikasi LSP
                        UPNVJ.</p>
                </div>

                <div class="flex items-center gap-3 justify-end">
                    <button @click="scroll('left')"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:border-[#ea580c] hover:bg-[#ea580c] hover:text-white hover:shadow-lg hover:shadow-orange-500/20 active:scale-95"
                        aria-label="Scroll kiri">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button @click="scroll('right')"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:border-[#ea580c] hover:bg-[#ea580c] hover:text-white hover:shadow-lg hover:shadow-orange-500/20 active:scale-95"
                        aria-label="Scroll kanan">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            @if (isset($articles) && $articles->count() > 0)
                <div x-ref="beritaContainer"
                    class="skema-scrollbar -mx-4 flex snap-x snap-mandatory gap-6 overflow-x-auto px-6 pb-3 sm:-mx-6 sm:px-6 lg:-mx-12 lg:gap-8 lg:px-12">
                    @foreach ($articles as $article)
                        <div class="w-[86vw] sm:w-[22rem] lg:w-[25rem] shrink-0 snap-center">
                            <x-public.article-card :article="$article" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-10 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <p class="font-medium">Belum ada berita atau pengumuman terbaru.</p>
                </div>
            @endif

            <div class="mt-12 text-center">
                <a href="{{ route('article.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 hover:text-[#ea580c] hover:border-[#ea580c] transition-colors gap-2">
                    Lihat Semua Berita
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

