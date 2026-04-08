<div class="min-h-screen bg-[#f8fafc]">
    <x-public.navbar active="skema" />

    <section
        class="relative overflow-hidden bg-[linear-gradient(135deg,_#183b68_0%,_#205b8f_45%,_#1fb6d9_100%)] px-6 pb-24 pt-36 lg:px-16">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.18),_transparent_28%)]">
        </div>
        <div class="absolute -left-24 top-24 h-56 w-56 rounded-full bg-white/10 blur-3xl">
        </div>
        <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-cyan-200/20 blur-3xl">
        </div>

        <div
            class="relative mx-auto flex w-full max-w-[85rem] flex-col gap-10 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl text-white">
                <p class="text-sm font-bold uppercase tracking-[0.28em] text-cyan-100/80">Skema Sertifikasi</p>
                <h1 class="mt-5 text-4xl font-bold leading-tight md:text-5xl lg:text-[3.6rem]">
                    Temukan jalur sertifikasi yang paling sesuai dengan kompetensi Anda.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-8 text-blue-50/88 md:text-lg">
                    Jelajahi skema aktif dari berbagai fakultas dan program studi UPA LUK, lalu pilih skema yang paling
                    relevan untuk pengembangan kompetensi Anda.
                </p>
            </div>

            <div class="grid max-w-xl gap-4 sm:grid-cols-3">
                <div class="rounded-3xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-100/80">Skema Aktif</p>
                    <p class="mt-3 text-3xl font-bold text-white">{{ $schemes->count() }}</p>
                </div>
                <div class="rounded-3xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-100/80">Fakultas</p>
                    <p class="mt-3 text-3xl font-bold text-white">{{ $faculties->count() }}</p>
                </div>
                <div class="rounded-3xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-100/80">
                        {{ $jenisSkemas->isNotEmpty() ? 'Jenis Skema' : 'Klasifikasi' }}
                    </p>
                    @if ($jenisSkemas->isNotEmpty())
                        <p class="mt-3 text-3xl font-bold text-white">{{ $jenisSkemas->count() }}</p>
                    @else
                        <p class="mt-3 text-lg font-bold text-white">Belum diisi</p>
                        <p class="mt-1 text-xs leading-5 text-blue-50/80">Contohnya: Okupasi, Klaster, atau KKNI.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <main class="px-6 pb-16 lg:px-16">
        <div class="relative z-10 mx-auto -mt-12 w-full max-w-[85rem] space-y-10">
            <div
                class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-[0_24px_60px_-36px_rgba(15,23,42,0.35)] md:p-6">
                <form wire:submit="applyFilters" class="flex flex-col gap-4 xl:flex-row xl:items-center">
                    <div class="relative xl:min-w-0 xl:flex-[1.7]">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" wire:model="searchInput" placeholder="Cari skema sertifikasi..."
                            class="block w-full rounded-2xl border border-slate-200 bg-slate-50 py-4 pl-11 pr-4 text-sm font-semibold text-slate-700 outline-none transition-all hover:border-slate-300 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10" />
                    </div>

                    @if ($jenisSkemas->isNotEmpty() || $faculties->isNotEmpty())
                        <div class="grid gap-4 md:grid-cols-2 xl:w-[23rem] xl:shrink-0">
                            @if ($jenisSkemas->isNotEmpty())
                                <select wire:model="filterTypeInput"
                                    class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-4 text-sm font-semibold text-slate-700 outline-none transition-all hover:border-slate-300 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">
                                    <option value="">Semua Jenis</option>
                                    @foreach ($jenisSkemas as $jenis)
                                        <option value="{{ $jenis }}">{{ $jenis }}</option>
                                    @endforeach
                                </select>
                            @endif

                            @if ($faculties->isNotEmpty())
                                <select wire:model="filterFacultyInput"
                                    class="block w-full rounded-2xl border border-slate-200 bg-white px-4 py-4 text-sm font-semibold text-slate-700 outline-none transition-all hover:border-slate-300 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10">
                                    <option value="">Semua Fakultas</option>
                                    @foreach ($faculties as $fac)
                                        <option value="{{ $fac->id }}">{{ $fac->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endif

                    <div class="xl:shrink-0">
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-2xl bg-[#14b8a6] px-6 py-4 text-sm font-semibold text-white transition hover:bg-[#0d9488] xl:min-w-32">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sky-700">Daftar Skema</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">Pilihan skema yang tersedia</h2>
                </div>

                <div class="flex gap-1 rounded-2xl bg-slate-100 p-1">
                    <button wire:click="$set('sortOption', 'terbaru')" @class([
                        'rounded-xl px-4 py-2.5 text-sm font-semibold transition-all',
                        'bg-white text-gray-900 shadow-sm' => $sortOption === 'terbaru',
                        'text-gray-500 hover:text-gray-700' => $sortOption !== 'terbaru',
                    ])>
                        Terbaru
                    </button>
                    <button wire:click="$set('sortOption', 'semua')" @class([
                        'rounded-xl px-4 py-2.5 text-sm font-semibold transition-all',
                        'bg-white text-gray-900 shadow-sm' => $sortOption === 'semua',
                        'text-gray-500 hover:text-gray-700' => $sortOption !== 'semua',
                    ])>
                        A-Z
                    </button>
                    <button wire:click="$set('sortOption', 'populer')" @class([
                        'rounded-xl px-4 py-2.5 text-sm font-semibold transition-all',
                        'bg-white text-gray-900 shadow-sm' => $sortOption === 'populer',
                        'text-gray-500 hover:text-gray-700' => $sortOption !== 'populer',
                    ])>
                        Populer
                    </button>
                </div>
            </div>

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
        </div>
    </main>

    <x-public.footer />

    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrolled"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed bottom-8 right-8 bg-[#1e40af] text-white p-3.5 rounded-full shadow-2xl hover:bg-blue-800 transition z-50 hover:-translate-y-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>
</div>
