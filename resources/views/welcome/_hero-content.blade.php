    <!-- Centered Content Section -->
    <div class="relative z-20 bg-white py-12 lg:py-16">
        <div class="mx-auto max-w-[76.5rem] px-4 text-center sm:px-6 lg:px-0">
            <div
                class="mb-6 inline-flex items-center rounded-full bg-[#17BC23] px-5 py-2 text-xs font-extrabold text-black shadow-sm sm:mb-8 sm:px-8 sm:py-3 sm:text-sm">
                LSP-P1 UPNVJ
            </div>

            <h1 class="mx-auto mb-6 max-w-4xl text-3xl font-black leading-tight text-gray-900 sm:text-5xl md:text-6xl">
                {{ $homeContent['hero_title'] ?? 'Uji Kompetensi Terakreditasi & Profesional' }}
            </h1>

            <p
                class="mx-auto mb-10 max-w-3xl text-sm font-medium leading-relaxed text-gray-600 sm:text-lg md:text-xl lg:mb-12">
                {{ $homeContent['hero_subtitle'] ?? 'Sistem penilaian dilakukan secara transparan dan objektif oleh Master Asesor yang memiliki keahlian spesifik menggunakan fasilitas berstandar industri.' }}
            </p>

            <div class="flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4">
                <a href="{{ $homeContent['cta_link'] ?? route('register') }}"
                    class="group/btn inline-flex min-h-12 w-full items-center justify-center gap-3 rounded-full bg-[#ea580c] px-8 py-3.5 text-center text-sm font-extrabold tracking-wide text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-[#c2410c] sm:w-auto sm:min-w-52">
                    {{ $homeContent['cta_text'] ?? 'DAFTAR SEKARANG' }}
                    <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                <a href="#skema-section"
                    @click.prevent="document.getElementById('skema-section')?.scrollIntoView({behavior: 'smooth'})"
                    class="inline-flex min-h-12 w-full items-center justify-center gap-3 rounded-full border-2 border-gray-200 bg-gray-50 px-8 py-3.5 text-center text-sm font-extrabold tracking-wide text-gray-900 transition hover:-translate-y-0.5 hover:border-gray-300 hover:bg-gray-100 sm:w-auto sm:min-w-52">
                    LIHAT SKEMA
                </a>
            </div>
        </div>
    </div>


    <!-- Floating Cek Sertifikat Widget -->
    <div class="relative z-20 pb-20 mx-auto w-full max-w-[76.5rem] px-4 sm:px-6 lg:px-0" x-data="{
        hasSearched: false,
        certificateName: '',
        certificateNumber: '',
        resultUrl() {
            const params = new URLSearchParams();
    
            if (this.certificateName.trim()) {
                params.set('nama', this.certificateName.trim());
            }
    
            if (this.certificateNumber.trim()) {
                params.set('nomor', this.certificateNumber.trim());
            }
    
            const query = params.toString();
    
            return @js(route('cek-sertifikat')) + (query ? `?${query}` : '');
        },
    }">
        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm sm:p-8">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="mb-1 text-xl font-black text-gray-950 sm:text-2xl">Periksa Sertifikat</h3>
                    <p class="text-sm font-medium text-gray-600 sm:text-base">
                        Verifikasi keaslian sertifikat kompetensi Anda di LSP UPNVJ secara online.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white px-4 py-4 sm:px-7 sm:py-5 shadow-sm">
                <div
                    class="grid items-end gap-4 sm:gap-6 lg:grid-cols-[1fr_1fr_auto_auto] lg:divide-x lg:divide-gray-200">
                    <form action="{{ route('cek-sertifikat') }}" method="GET" id="cek-form"
                        @submit.prevent="hasSearched = true" class="contents">
                        <div class="min-w-0 lg:pr-6">
                            <label for="nama"
                                class="mb-2 block text-xs font-black uppercase tracking-wider text-gray-400">Nama
                                Lengkap</label>
                            <input type="text" id="nama" name="nama" placeholder="Isi Nama Lengkap"
                                x-model="certificateName"
                                class="h-12 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm font-semibold text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 lg:h-11">
                        </div>
                        <div class="min-w-0 lg:px-6">
                            <label for="reg_no"
                                class="mb-2 block text-xs font-black uppercase tracking-wider text-gray-400">No.
                                Registrasi / Sertifikat</label>
                            <input type="text" id="reg_no" name="nomor" placeholder="Contoh: 12345"
                                x-model="certificateNumber"
                                class="h-12 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm font-semibold text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 lg:h-11">
                        </div>
                    </form>

                    <div class="lg:px-6">
                        <button type="submit" form="cek-form"
                            class="flex h-12 w-full items-center justify-center gap-3 rounded-xl bg-[#004cad] px-8 text-sm font-black text-white transition hover:bg-[#003d91] sm:h-11 lg:w-40">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            CARI
                        </button>
                    </div>

                    <div class="flex flex-col rounded-xl bg-gray-50 p-4 lg:bg-transparent lg:p-0 lg:pl-6" x-cloak
                        x-show="hasSearched" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <span class="text-sm font-extrabold leading-tight text-gray-950"
                            x-text="certificateName || 'Sertifikat Ditemukan'"></span>
                        <a href="{{ route('cek-sertifikat') }}" :href="resultUrl()"
                            class="mt-1 inline-flex items-center gap-1 text-xs font-black text-[#004cad] transition hover:text-[#003d91] uppercase tracking-wider">
                            Lihat Detail
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


