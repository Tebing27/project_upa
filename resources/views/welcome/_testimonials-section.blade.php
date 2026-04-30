    <!-- Testimoni Section -->
    <div class="relative py-28 overflow-hidden z-10 flex items-center">
        <!-- Background Image & Overlay -->
        <img src="{{ asset('assets/background.webp') }}" alt="Campus Background"
            class="absolute inset-0 w-full h-full object-cover z-0 filter blur-[2px] scale-105">
        <div class="absolute inset-0 bg-slate-900/80 z-0"></div>

        <div class="max-w-[85rem] mx-auto w-full relative z-10 px-4 sm:px-6 md:px-12">
            <div class="text-center mb-16">
                <h4 class="text-[#f97316] font-bold tracking-widest mb-4 text-sm uppercase">CERITA ALUMNI</h4>
                <h2 class="text-4xl font-bold text-white drop-shadow-md">Apa Kata Mereka?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @if (isset($homeContent['testimonials']) && count($homeContent['testimonials']) > 0)
                    @foreach ($homeContent['testimonials'] as $testimonial)
                        @if (isset($testimonial['quote']) && $testimonial['quote'])
                            <div
                                class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                                <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-blue-50"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z">
                                    </path>
                                </svg>
                                <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">
                                    "{{ $testimonial['quote'] }}"</p>
                                <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                                    @if (isset($testimonial['avatar']) && $testimonial['avatar'])
                                        <img src="{{ $testimonial['avatar'] }}"
                                            alt="{{ $testimonial['author'] ?? 'Anonim' }}"
                                            class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                            {{ strtoupper(substr($testimonial['author'] ?? 'A', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $testimonial['author'] ?? 'Anonim' }}
                                        </h4>
                                        @if (isset($testimonial['role']) && $testimonial['role'])
                                            <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">
                                                {{ $testimonial['role'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <!-- Testimoni 1 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                        <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-blue-50"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z">
                            </path>
                        </svg>
                        <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">"Memiliki sertifikat
                            kompetensi Web Programmer dari BNSP membuat portofolio dan CV saya jauh lebih menonjol di
                            mata rekruter. Proses sertifikasinya di UPA LUK sangat profesional dan membantu saya saat
                            wawancara kerja pertama."</p>
                        <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                            <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=eff6ff&color=1e40af&bold=true"
                                alt="Budi Santoso" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-gray-900">Budi Santoso</h4>
                                <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">Alumni
                                    Informatika</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimoni 2 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                        <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-orange-50"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z">
                            </path>
                        </svg>
                        <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">"Asesornya sangat
                            kompeten. Ujiannya sungguh relevan dengan kebutuhan industri saat ini, tidak sekadar menguji
                            teori semata. Berkat lisensi ini, saya lebih percaya diri saat melamar posisi UI/UX Designer
                            internship."</p>
                        <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                            <img src="https://ui-avatars.com/api/?name=Siti+Aminah&background=fff7ed&color=c2410c&bold=true"
                                alt="Siti Aminah" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-gray-900">Siti Aminah</h4>
                                <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">Mahasiswa
                                    Sistem Informasi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimoni 3 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                        <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-emerald-50"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z">
                            </path>
                        </svg>
                        <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">"Sistem
                            pendaftarannya sangat mudah dan layanannya benar-benar responsif. Fasilitas TUK (Tempat Uji
                            Kompetensi) yang disediakan langsung oleh kampus UPNVJ juga terbukti sangat lengkap setara
                            standar BNSP."</p>
                        <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                            <img src="https://ui-avatars.com/api/?name=Randi+Pratama&background=ecfdf5&color=047857&bold=true"
                                alt="Randi Pratama" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-gray-900">Randi Pratama</h4>
                                <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">Alumni D3
                                    Keperawatan</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

