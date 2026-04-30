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
