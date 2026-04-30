            @if ($schemes->isEmpty())
                <div
                    class="rounded-[2rem] border border-slate-200/80 bg-white px-6 py-20 text-center shadow-[0_20px_50px_-35px_rgba(15,23,42,0.25)]">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400 mx-auto">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-gray-900">Tidak ada skema ditemukan</h3>
                    <p class="mt-2 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($schemes as $scheme)
                        <div
                            class="group flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200/70 bg-white shadow-[0_18px_45px_-30px_rgba(15,23,42,0.22)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_24px_60px_-28px_rgba(15,23,42,0.3)]">
                            <div
                                class="relative h-52 overflow-hidden bg-[linear-gradient(135deg,_#0f766e_0%,_#14b8a6_45%,_#67e8f9_100%)]">
                                @if ($scheme->gambar_path)
                                    <img src="{{ Storage::url($scheme->gambar_path) }}" alt="{{ $scheme->name }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <svg class="h-14 w-14 text-white/50" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                    </div>
                                @endif
                                @if ($scheme->jenis_skema)
                                    <div class="absolute left-4 top-4">
                                        <span
                                            class="inline-flex items-center rounded-full bg-white/90 px-3.5 py-1.5 text-xs font-bold text-slate-800 shadow-sm backdrop-blur-sm">
                                            {{ $scheme->jenis_skema }}
                                        </span>
                                    </div>
                                @endif
                                @if ($scheme->is_popular)
                                    <div class="absolute right-4 top-4">
                                        <span
                                            class="inline-flex items-center rounded-full bg-amber-300 px-3.5 py-1.5 text-xs font-bold text-amber-950 shadow-sm">
                                            Populer
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-6">
                                <div class="flex-1">
                                    <p class="line-clamp-1 text-xs font-semibold uppercase tracking-[0.18em] text-sky-700">
                                        {{ $scheme->faculty ?? 'Umum' }}
                                        @if ($scheme->study_program)
                                            <span class="normal-case tracking-normal text-slate-400">/
                                                {{ $scheme->study_program }}</span>
                                        @endif
                                    </p>
                                    <h3 class="mt-3 min-h-[3.5rem] line-clamp-2 text-xl font-bold leading-snug text-slate-900">
                                        {{ $scheme->name }}
                                    </h3>
                                    @if ($scheme->description)
                                        <p class="mt-3 min-h-[5.25rem] line-clamp-3 text-sm leading-7 text-slate-600">
                                            {{ $scheme->description }}
                                        </p>
                                    @else
                                        <p class="mt-3 min-h-[5.25rem] text-sm leading-7 text-slate-400">
                                            Deskripsi skema belum tersedia.
                                        </p>
                                    @endif
                                    @if ($scheme->harga)
                                        <p class="mt-4 text-sm font-bold text-teal-700">Rp.
                                            {{ number_format((float) $scheme->harga, 0, ',', '.') }}</p>
                                    @endif
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('skema.detail', $scheme) }}" wire:navigate
                                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-[#183b68] px-4 py-3 text-sm font-semibold text-white transition-all hover:bg-[#122e52]">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
