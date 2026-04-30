    <div class="space-y-6">
        @forelse($this->groupedSchemes as $faculty => $programs)
            <div class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="border-b border-gray-50 bg-gray-50/30 px-6 py-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">{{ $faculty }}</h2>
                </div>

                <div class="divide-y divide-gray-50">
                    @foreach ($programs as $program => $schemes)
                        <div class="p-6">
                            <h3 class="mb-5 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $program }}
                            </h3>

                            <div class="overflow-hidden rounded-xl border border-gray-100">
                                <table class="w-full text-left">
                                    <thead class="bg-gray-50/50">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 w-1/3">
                                                Nama Skema</th>
                                            <th
                                                class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                                Deskripsi</th>
                                            <th
                                                class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center w-40">
                                                Template APL 02</th>
                                            <th
                                                class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center w-32">
                                                Aktif</th>
                                            <th
                                                class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center w-32">
                                                Populer</th>
                                            <th
                                                class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right w-36">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 bg-white">
                                        @foreach ($schemes as $scheme)
                                            <tr class="group transition-colors hover:bg-gray-50/30">
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $scheme->name }}
                                                    </div>
                                                    @if ($scheme->kode_skema)
                                                        <p class="text-xs text-gray-400">{{ $scheme->kode_skema }}</p>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    <p class="text-xs text-gray-500 line-clamp-1">
                                                        {{ $scheme->description ?: '-' }}</p>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if ($scheme->apl_02_template_path)
                                                        <a href="{{ Storage::url($scheme->apl_02_template_path) }}" download
                                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">
                                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16" />
                                                            </svg>
                                                            Tersedia
                                                        </a>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-500">
                                                            Belum ada
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <button type="button" wire:click="toggleActive({{ $scheme->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="toggleActive({{ $scheme->id }})"
                                                        @class([
                                                            'inline-flex h-7 w-12 items-center rounded-full border px-1 transition',
                                                            'justify-end border-emerald-200 bg-emerald-500' => $scheme->is_active,
                                                            'justify-start border-slate-200 bg-slate-300' => ! $scheme->is_active,
                                                        ])>
                                                        <span class="h-5 w-5 rounded-full bg-white shadow-sm"></span>
                                                    </button>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <button type="button" wire:click="togglePopular({{ $scheme->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="togglePopular({{ $scheme->id }})"
                                                        @class([
                                                            'inline-flex h-7 w-12 items-center rounded-full border px-1 transition',
                                                            'justify-end border-amber-200 bg-amber-400' => $scheme->is_popular,
                                                            'justify-start border-slate-200 bg-slate-300' => ! $scheme->is_popular,
                                                        ])>
                                                        <span class="h-5 w-5 rounded-full bg-white shadow-sm"></span>
                                                    </button>
                                                </td>
                                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                                    <a href="{{ route('admin.schemes.edit', $scheme) }}" wire:navigate
                                                        class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors mr-4">Edit</a>
                                                    <button wire:click="confirmDelete({{ $scheme->id }})"
                                                        class="text-sm font-semibold text-red-600 hover:text-red-700 transition-colors">Hapus</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-20 text-center rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400 mx-auto">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-bold text-gray-900">Belum ada Skema</h3>
                <p class="mt-2 text-sm text-gray-500">Tambahkan skema sertifikasi baru untuk mulai mengelola pangkalan
                    data skema uji.</p>
            </div>
        @endforelse
    </div>
