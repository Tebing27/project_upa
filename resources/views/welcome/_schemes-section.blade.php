    <!-- Skema Section -->
    <div id="skema-section" class="relative z-10 py-20 pb-28 scroll-mt-24 sm:py-24 lg:py-28" x-data="{
        scroll(direction) {
            const container = this.$refs.skemaContainer;
            const scrollAmount = container.clientWidth * 0.8;
            container.scrollBy({
                left: direction === 'left' ? -scrollAmount : scrollAmount,
                behavior: 'smooth'
            });
        }
    }">
        <div class="relative mx-auto w-full max-w-[85rem] px-4 sm:px-6 md:px-12">
            <div class="mb-8 flex flex-col gap-6 lg:mb-10 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-black leading-tight text-gray-950 sm:text-4xl lg:text-[2.75rem]">
                        Skema <span class="text-[#ea580c]">Sertifikasi</span></h2>
                    <p class="mt-3 max-w-xl text-sm font-medium leading-relaxed text-gray-600 sm:text-base">
                        Pilih skema kompetensi sesuai bidang Anda dan mulai proses sertifikasi resmi UPA-LUK UPNVJ.
                    </p>
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

            @if (empty($latestSchemes) || $latestSchemes->isEmpty())
                <div class="text-center text-gray-500 py-10 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <p class="font-medium">Belum ada skema tersedia.</p>
                </div>
            @else
                <div x-ref="skemaContainer"
                    class="skema-scrollbar -mx-4 flex snap-x snap-mandatory gap-5 overflow-x-auto px-6 pb-3 sm:-mx-6 sm:px-6 lg:-mx-12 lg:gap-6 lg:px-12">
                    @foreach ($latestSchemes as $scheme)
                        <article
                            class="group flex w-[86vw] max-w-[22rem] shrink-0 snap-center flex-col overflow-hidden rounded-[1.25rem] bg-white transition duration-300 hover:-translate-y-1 sm:w-[20rem] lg:w-[21rem]">
                            <a href="{{ route('skema.detail', $scheme) }}"
                                class="relative block aspect-[4/3] bg-slate-200">
                                @if ($scheme->gambar_path)
                                    <img src="{{ Storage::url($scheme->gambar_path) }}" alt="{{ $scheme->name }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <img src="https://images.unsplash.com/photo-1777047023536-8e47688b77f9?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                        alt="{{ $scheme->name }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @endif

                                @if ($scheme->jenis_skema)
                                    <span
                                        class="absolute right-4 top-4 inline-flex max-w-[calc(100%-2rem)] items-center rounded-full bg-white/95 px-3 py-1.5 text-xs font-extrabold text-gray-900 shadow-sm backdrop-blur-sm">
                                        {{ $scheme->jenis_skema }}
                                    </span>
                                @endif

                                <span
                                    class="absolute -bottom-4 right-5 inline-flex items-center gap-1 rounded-full bg-white px-3 py-1.5 text-[0.7rem] font-extrabold text-gray-900 shadow-lg shadow-black/10">
                                    <svg class="h-3.5 w-3.5 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.95-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    Tersedia
                                </span>
                            </a>

                            <div
                                class="relative z-10 -mt-3 flex flex-1 flex-col rounded-t-[1.25rem] bg-white px-5 pb-5 pt-8">
                                <div class="flex-1">
                                    <h3
                                        class="line-clamp-2 text-lg font-black leading-snug text-gray-950 transition group-hover:text-[#ea580c]">
                                        <a href="{{ route('skema.detail', $scheme) }}">{{ $scheme->name }}</a>
                                    </h3>

                                    <p class="mt-3 line-clamp-3 text-sm font-medium leading-relaxed text-gray-600">
                                        {{ $scheme->description ?: 'Skema ini dirancang untuk menguji kompetensi peserta sesuai standar yang ditetapkan.' }}
                                    </p>
                                </div>

                                <div class="mt-5 flex items-end justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-extrabold tracking-wide text-gray-400">
                                            Mulai dari</p>
                                        <p class="mt-1 text-base font-black text-gray-950">
                                            @if ($scheme->harga)
                                                Rp{{ number_format((float) $scheme->harga, 0, ',', '.') }}
                                            @else
                                                Hubungi Admin
                                            @endif
                                        </p>
                                    </div>
                                    <a href="{{ route('skema.detail', $scheme) }}"
                                        class="inline-flex min-h-10 shrink-0 items-center justify-center rounded-full bg-gray-100 px-4 text-xs font-extrabold text-gray-950 transition hover:bg-[#ea580c] hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-[#ea580c]">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 text-center">
                    <a href="{{ route('skema.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-8 py-3.5 text-sm font-extrabold text-gray-700 shadow-sm transition hover:border-[#ea580c] hover:bg-gray-50 hover:text-[#ea580c] hover:shadow-md">
                        Lihat Semua Skema
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

