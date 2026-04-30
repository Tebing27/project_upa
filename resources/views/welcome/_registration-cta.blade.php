    <!-- Registration Section -->
    <div class="relative z-10 bg-white py-16 sm:py-20 lg:py-24">
        <div class="relative mx-auto w-full max-w-[85rem] px-4 sm:px-6 md:px-12">
            <div
                class="relative overflow-hidden rounded-lg border border-green-300/60 bg-[#17BC23] px-5 py-12 text-center shadow-[0_28px_70px_-35px_rgba(15,23,42,0.55)] sm:px-8 sm:py-14 lg:px-16 lg:py-20">
                <div class="absolute -left-8 top-6 flex gap-4 opacity-15 sm:left-8 sm:top-8">
                    <span
                        class="flex h-16 w-16 rotate-[-10deg] items-center justify-center rounded-full border border-white/50 bg-white/20 text-white">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 3 2.5 8l9.5 5 9.5-5L12 3Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M6.5 10.5v4.25c0 1.8 2.45 3.25 5.5 3.25s5.5-1.45 5.5-3.25V10.5"></path>
                        </svg>
                    </span>
                    <span
                        class="hidden h-14 w-14 rotate-6 items-center justify-center rounded-full border border-white/50 bg-white/20 text-white sm:flex">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M5 4.5h10.5A2.5 2.5 0 0 1 18 7v13H7.5A2.5 2.5 0 0 1 5 17.5v-13Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 8h7M8 11h7M8 17.5A2.5 2.5 0 0 1 10.5 15H18"></path>
                        </svg>
                    </span>
                </div>
                <div class="absolute -right-4 bottom-4 flex gap-4 opacity-15 sm:right-10 sm:bottom-8">
                    <span
                        class="hidden h-16 w-16 items-center justify-center rounded-full border border-white/50 bg-white/20 text-white sm:flex">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 4h8a2 2 0 0 1 2 2v14l-4-2-4 2-4-2-4 2V6a2 2 0 0 1 2-2Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 8h8M8 11h8M8 14h4"></path>
                        </svg>
                    </span>
                    <span
                        class="flex h-20 w-20 rotate-12 items-center justify-center rounded-full border border-white/50 bg-white/20 text-white">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M6 4.5h12v15H6z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 8h6M9 11.5h6M9 15h3"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M16 17.5 18 20l2-2.5V14h-4v3.5Z"></path>
                        </svg>
                    </span>
                </div>

                <div class="relative mx-auto flex max-w-4xl flex-col items-center gap-6">
                    <h2 class="text-3xl font-black leading-tight text-white sm:text-4xl lg:text-5xl">
                        Pendaftaran Dibuka
                    </h2>

                    @if (isset($homeContent['reg_desc']) && $homeContent['reg_desc'])
                        <div
                            class="max-w-3xl text-base font-semibold leading-relaxed text-white content-html sm:text-lg">
                            {!! $homeContent['reg_desc'] !!}
                        </div>
                    @else
                        <p class="max-w-3xl text-base font-semibold leading-relaxed text-white sm:text-lg">
                            Unit Penunjang Akademik-layanan Uji Kompetensi (UPA-LUK) UPN "Veteran" Jakarta membuka
                            pendaftaran Uji Kompetensi Batch 2 Tahun 2025
                        </p>
                    @endif

                    <div
                        class="inline-flex w-full flex-col items-center justify-center gap-1 rounded-lg border border-white/45 bg-white/20 px-5 py-4 text-white backdrop-blur-sm sm:w-auto sm:min-w-96 sm:px-8">
                        <p class="text-xs font-black uppercase text-white/80">
                            {{ $homeContent['reg_period_label'] ?? 'Periode Pendaftaran' }}
                        </p>
                        <p class="text-lg font-black leading-snug sm:text-2xl">
                            {{ $homeContent['reg_period_value'] ?? '30 September - 19 Oktober 2025' }}
                        </p>
                    </div>

                    <div class="flex flex-row flex-wrap justify-center gap-3 pt-2 sm:gap-4">
                        <a href="{{ route('register') }}"
                            class="inline-flex min-h-12 min-w-32 items-center justify-center rounded-full bg-[#004cad] px-7 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-blue-950/20 transition hover:-translate-y-0.5 hover:bg-[#003d91] focus:outline-2 focus:outline-offset-2 focus:outline-blue-900 sm:min-w-44 sm:px-8">
                            Daftar
                        </a>
                        <a href="#skema-section"
                            @click.prevent="document.getElementById('skema-section')?.scrollIntoView({behavior: 'smooth'})"
                            class="inline-flex min-h-12 min-w-32 items-center justify-center rounded-full border border-white/70 bg-white/75 px-7 py-3.5 text-sm font-extrabold text-slate-950 shadow-lg shadow-white/20 transition hover:-translate-y-0.5 hover:bg-white focus:outline-2 focus:outline-offset-2 focus:outline-white sm:min-w-44 sm:px-8">
                            Lihat Skema
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

