    <!-- Selamat Datang Section -->
    <div class="pt-32 pb-24 bg-white slanted-top relative z-10 mt-12 md:mt-0">
        <div class="max-w-[85rem] mx-auto w-full px-4 sm:px-6 md:px-12">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="pr-0 lg:pr-8">
                    <h2 class="text-4xl lg:text-[2.5rem] font-bold mb-6 text-gray-900 leading-tight">Selamat Datang di
                        UPA-LUK</h2>
                    <div class="text-gray-600 mb-8 leading-relaxed text-lg content-html">
                        {!! $homeContent['description'] ??
                            'Lembaga Sertifikasi Profesi (LSP) UPN "Veteran" Jakarta melayani pelaksanaan kegiatan uji kompetensi mahasiswa dengan lisensi resmi dari Badan Nasional Sertifikasi Profesi (BNSP).' !!}
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                        <!-- Benefit 1 -->
                        <div class="flex items-start gap-4">
                            <div
                                class="bg-blue-50 p-2.5 rounded-lg text-blue-600 shadow-sm border border-blue-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Terlisensi BNSP</h4>
                                <p class="text-sm text-gray-600">Sertifikat valid & diakui secara nasional.</p>
                            </div>
                        </div>
                        <!-- Benefit 2 -->
                        <div class="flex items-start gap-4">
                            <div
                                class="bg-orange-50 p-2.5 rounded-lg text-[#ea580c] shadow-sm border border-orange-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Asesor Kompeten</h4>
                                <p class="text-sm text-gray-600">Teruji dan berpengalaman di industrinya.</p>
                            </div>
                        </div>
                        <!-- Benefit 3 -->
                        <div class="flex items-start gap-4">
                            <div
                                class="bg-emerald-50 p-2.5 rounded-lg text-emerald-600 shadow-sm border border-emerald-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">TUK Standar</h4>
                                <p class="text-sm text-gray-600">Perlengkapan uji kompetensi yang memadai.</p>
                            </div>
                        </div>
                        <!-- Benefit 4 -->
                        <div class="flex items-start gap-4">
                            <div
                                class="bg-purple-50 p-2.5 rounded-lg text-purple-600 shadow-sm border border-purple-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Akses Mudah</h4>
                                <p class="text-sm text-gray-600">Pendaftaran & info cepat via portal.</p>
                            </div>
                        </div>
                    </div>
                    <a href="#"
                        class="bg-[#38bdf8] hover:bg-[#0ea5e9] text-white px-8 py-3.5 rounded text-sm tracking-wide font-bold transition inline-block shadow-md shadow-sky-200">
                        TENTANG KAMI
                    </a>
                </div>
                <div class="overflow-hidden rounded-2xl border-4 border-gray-100 shadow-2xl">
                    <div class="aspect-video w-full bg-slate-950">
                        <iframe class="h-full w-full"
                            src="{{ $homeContent['youtube_url'] ?? config('services.youtube.intro_video', 'https://www.youtube.com/embed/shb_YrytjFM') }}"
                            title="Video Pengenalan Aplikasi LSP"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                        </iframe>
                    </div>
                    <div class="bg-[#2f4779] px-6 py-5 text-white">
                        <p class="text-sm font-semibold tracking-[0.18em] text-sky-200">VIDEO PENGENALAN</p>
                        <p class="mt-1 text-lg font-bold">Aplikasi LSP UPA LUK UPN Veteran Jakarta</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div> <!-- Close Selamat Datang wrapper early -->

