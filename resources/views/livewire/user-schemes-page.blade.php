<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Skema Sertifikasi</h1>
            <p class="mt-1 text-sm text-gray-500">Pilih skema sertifikasi yang tersedia untuk Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="filterPopularity"
                class="block w-40 px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                <option value="">Semua Skema</option>
                <option value="populer">Populer</option>
            </select>
            <select wire:model.live="filterFaculty"
                class="block w-48 px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                <option value="">Semua Fakultas</option>
                @foreach ($faculties as $fac)
                    <option value="{{ $fac }}">{{ $fac }}</option>
                @endforeach
            </select>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari skema..."
                    class="block w-56 pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white" />
            </div>
        </div>
    </div>

    {{-- Scheme Cards Grid --}}
    @if ($schemes->isEmpty())
        <div class="py-20 text-center rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400 mx-auto">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-bold text-gray-900">Tidak ada skema ditemukan</h3>
            <p class="mt-2 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($schemes as $scheme)
                <div
                    class="group flex flex-col overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] transition-all hover:shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)]">
                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden bg-linear-to-br from-emerald-400 to-teal-500">
                        @if ($scheme->gambar_path)
                            <img src="{{ Storage::url($scheme->gambar_path) }}" alt="{{ $scheme->name }}"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-full items-center justify-center">
                                <svg class="h-16 w-16 text-white/50" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                        @endif
                        @if ($scheme->jenis_skema)
                            <div class="absolute top-3 left-3">
                                <span
                                    class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-gray-700 backdrop-blur-sm">
                                    {{ $scheme->jenis_skema }}
                                </span>
                            </div>
                        @endif
                        @if ($scheme->is_popular)
                            <div class="absolute top-3 right-3">
                                <span
                                    class="inline-flex items-center rounded-full bg-amber-400 px-3 py-1 text-xs font-bold text-amber-950 shadow-sm">
                                    Populer
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex-1">
                            <h3 class="text-base font-bold text-gray-900 line-clamp-2">{{ $scheme->name }}</h3>
                            <p class="mt-1 text-xs text-gray-400">{{ $scheme->faculty }} &mdash;
                                {{ $scheme->study_program }}</p>
                            @if ($scheme->description)
                                <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $scheme->description }}</p>
                            @endif
                            @if ($scheme->harga)
                                <p class="mt-2 text-sm font-bold text-emerald-600">Rp.
                                    {{ number_format((float) $scheme->harga, 0, ',', '.') }}</p>
                            @endif
                        </div>

                        {{-- Buttons --}}
                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('skema.detail', $scheme) }}" wire:navigate
                                class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-50">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('dashboard.daftar-skema', ['scheme' => $scheme->id, 'type' => 'baru', 'source' => 'dashboard-skema']) }}"
                                wire:navigate
                                class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-emerald-400 px-4 py-2.5 text-sm font-semibold text-black transition-all hover:bg-emerald-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Daftar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
