<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Manajemen Skema</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola daftar skema sertifikasi yang tersedia.</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="filterFaculty"
                class="block w-48 px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                <option value="">Semua Fakultas</option>
                @foreach ($this->availableFaculties as $facultyOption)
                    <option value="{{ $facultyOption->id }}">{{ $facultyOption->name }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.schemes.create') }}" wire:navigate
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-semibold text-black hover:bg-emerald-500 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Skema
            </a>
        </div>
    </div>

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

    {{-- Modal Delete --}}
    <div x-data="{ show: false }"
        x-on:open-modal.window="let n = $event.detail?.name || (Array.isArray($event.detail) ? $event.detail[0] : $event.detail); if (n === 'modal-scheme-delete') show = true"
        x-on:close-modal.window="let n = $event.detail?.name || (Array.isArray($event.detail) ? $event.detail[0] : $event.detail); if (n === 'modal-scheme-delete') show = false"
        x-on:keydown.escape.window="show = false" x-show="show" class="relative z-50" style="display: none;">

        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/40 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95" @click.outside="show = false"
                    class="relative overflow-hidden rounded-[2rem] w-full bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-lg">

                    <div class="p-6 md:p-8">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Hapus Skema</h3>
                                <p class="mt-2 text-sm text-gray-500 leading-relaxed">Apakah Anda yakin ingin menghapus
                                    skema ini? Data skema yang dihapus tidak dapat dikembalikan. Pastikan tidak ada
                                    peserta yang terkait dengan skema ini.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end gap-4 bg-slate-50/50 px-6 py-5 md:px-8 border-t border-slate-100">
                        <button type="button" @click="show = false"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="button" wire:click="delete"
                            class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-white bg-red-600 rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20">
                            <span wire:loading.remove wire:target="delete">Ya, Hapus Skema</span>
                            <span wire:loading wire:target="delete">Menghapus...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
