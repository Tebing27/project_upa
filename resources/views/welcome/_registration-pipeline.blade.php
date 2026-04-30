    <!-- Pendaftaran Pipeline Section -->
    <div class="bg-slate-50 border-t border-gray-100 py-20 lg:py-32 relative z-10 overflow-hidden shadow-inner">
        <!-- Subtle dotted background -->
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: radial-gradient(#000 1.5px, transparent 1.5px); background-size: 28px 28px;">
        </div>

        <div class="max-w-[85rem] mx-auto w-full relative z-10 px-4 sm:px-6 md:px-12">
            <div class="text-center mb-12 lg:mb-20">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-950 leading-tight">Langkah Mudah <span
                        class="text-[#ea580c]">Mendapatkan Sertifikat</span></h2>
                <p class="text-gray-600 mt-2 text-base sm:text-lg max-w-2xl mx-auto">Proses sertifikasi kompetensi di
                    UPA LUK
                    dirancang
                    agar cepat, transparan, dan terstruktur.</p>
            </div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-stretch lg:justify-between lg:gap-0">
                @for ($i = 1; $i <= 5; $i++)
                    @php
                        $defaultTitles = [
                            1 => 'Daftar Akun',
                            2 => 'Verifikasi Berkas',
                            3 => 'Pembayaran VA',
                            4 => 'Ujian Kompetensi',
                            5 => 'Terbit Sertifikat',
                        ];
                        $defaultDescs = [
                            1 => 'Buat akun untuk masuk ke portal',
                            2 => 'Upload dokumen persyaratan',
                            3 => 'Selesaikan biaya administrasi',
                            4 => 'Jadwal asesmen tatap muka / online',
                            5 => 'Sertifikat BNSP dirilis ke akun',
                        ];
                        $icons = [
                            1 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>',
                            2 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>',
                            3 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
                            4 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>',
                            5 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
                        ];
                        $title = $homeContent["step_{$i}_title"] ?? $defaultTitles[$i];
                        $desc = $homeContent["step_{$i}_desc"] ?? $defaultDescs[$i];
                    @endphp

                    @if ($title)
                        <div
                            class="relative flex items-start gap-4 sm:gap-5 lg:flex-1 lg:flex-col lg:items-center lg:gap-0 group">
                            @if ($i < 5)
                                <div
                                    class="absolute hidden lg:block w-full left-[50%] top-[4.5rem] px-12 z-[15] pointer-events-none transition-all duration-500 group-hover:-translate-y-8">
                                    <svg class="w-full h-20 overflow-visible block" preserveAspectRatio="none"
                                        viewBox="0 0 100 100">
                                        <defs>
                                            <marker id="arrowhead-{{ $i }}" markerWidth="7"
                                                markerHeight="7" refX="6" refY="3.5" orient="auto"
                                                markerUnits="strokeWidth">
                                                <path d="M1,1 L6,3.5 L1,6" fill="none" stroke="#64748b"
                                                    stroke-width="1.35" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </marker>
                                        </defs>
                                        @if ($i % 2 !== 0)
                                            <path d="M 0,50 C 25,10 75,10 100,50" fill="none" stroke="#64748b"
                                                stroke-width="1.6" stroke-dasharray="4 8" stroke-dashoffset="0"
                                                stroke-linecap="round"
                                                marker-end="url(#arrowhead-{{ $i }})"
                                                vector-effect="non-scaling-stroke" />
                                        @else
                                            <path d="M 0,50 C 25,90 75,90 100,50" fill="none" stroke="#64748b"
                                                stroke-width="1.6" stroke-dasharray="4 8" stroke-dashoffset="0"
                                                stroke-linecap="round"
                                                marker-end="url(#arrowhead-{{ $i }})"
                                                vector-effect="non-scaling-stroke" />
                                        @endif
                                    </svg>
                                </div>

                                <div
                                    class="absolute lg:hidden left-8 top-14 h-[calc(100%+1.25rem)] w-5 -translate-x-1/2 z-[5] pointer-events-none">
                                    <svg class="w-full h-full overflow-visible block" preserveAspectRatio="none"
                                        viewBox="0 0 20 100">
                                        <defs>
                                            <marker id="arrowhead-down-{{ $i }}" markerWidth="7"
                                                markerHeight="7" refX="3.5" refY="5.5" orient="auto"
                                                markerUnits="strokeWidth">
                                                <path d="M1,1 L3.5,6 L6,1" fill="none" stroke="#64748b"
                                                    stroke-width="1.35" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </marker>
                                        </defs>

                                        <path d="M10,0 C10,34 10,66 10,100" fill="none" stroke="#64748b"
                                            stroke-width="1.6" stroke-dasharray="4 8" stroke-dashoffset="0"
                                            stroke-linecap="round"
                                            marker-end="url(#arrowhead-down-{{ $i }})"
                                            vector-effect="non-scaling-stroke" class="process-arrow-path" />
                                    </svg>
                                </div>
                            @endif

                            <div
                                class="relative z-10 flex w-full items-center gap-4 transition-all duration-500 lg:flex-col lg:items-center lg:px-6 lg:py-12 lg:rounded-3xl lg:group-hover:-mt-8 lg:group-hover:bg-white lg:group-hover:shadow-[0_32px_64px_-16px_rgba(0,0,0,0.12)]">
                                <div
                                    class="relative z-20 flex h-16 w-16 shrink-0 rounded-full items-center justify-center transition-all duration-500 bg-white text-[#004cad] shadow-lg border border-gray-100 group-hover:bg-[#004cad] group-hover:text-white group-hover:shadow-xl group-hover:shadow-blue-500/20 lg:mb-8 lg:h-20 lg:w-20">
                                    <svg class="w-8 h-8 lg:w-10 lg:h-10" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        {!! $icons[$i] !!}
                                    </svg>
                                </div>

                                <div
                                    class="ml-4 lg:flex-none lg:border-0 lg:bg-transparent lg:p-0 lg:text-center lg:shadow-none lg:backdrop-blur-none">
                                    <h4
                                        class="text-base sm:text-lg lg:text-xl font-extrabold text-gray-950 mb-1.5 lg:mb-3 transition-colors duration-500">
                                        {{ $title }}</h4>
                                    <p
                                        class="text-sm font-medium text-gray-500 leading-relaxed max-w-none lg:max-w-[180px] lg:mx-auto transition-colors duration-500">
                                        {{ $desc }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
    </div>



    </div>
    </div>

