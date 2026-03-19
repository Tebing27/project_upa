<div class="space-y-8 p-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Jadwal Uji</h1>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Kelola peserta yang siap dijadwalkan dan yang sudah memiliki jadwal dalam satu tabel.</p>
        </div>
    </div>

    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Filter Jadwal</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Cari peserta berdasarkan nama atau NIM. Filter tanggal hanya diterapkan pada peserta yang sudah terjadwal.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="relative w-full md:w-72">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama / NIM..." class="block w-full rounded-xl border-0 py-2 pl-10 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-zinc-900 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:focus:ring-white sm:text-sm">
                </div>

                <div class="w-full md:w-56">
                    <input wire:model.live="filterDate" type="date" class="block w-full rounded-xl border-0 py-2 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-zinc-900 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:focus:ring-white sm:text-sm">
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Peserta Jadwal Uji</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Peserta dengan status Dokumen OK dapat dijadwalkan, sedangkan peserta terjadwal bisa langsung diedit atau dihapus jadwalnya.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700 ring-1 ring-inset ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-200 dark:ring-zinc-700">
                    {{ $registrations->count() }} peserta
                </span>
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800/70">
                    {{ $readyRegistrationsCount }} belum dijadwalkan
                </span>
                <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-800/70">
                    {{ $scheduledRegistrationsCount }} terjadwal
                </span>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto rounded-2xl border border-zinc-200 dark:border-zinc-800">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Prodi / Skema</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Status / Jadwal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Lokasi / Asesor</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                    @forelse ($registrations as $registration)
                        <tr wire:key="registration-{{ $registration->id }}" @class([
                            'bg-emerald-50/80 dark:bg-emerald-900/10' => $highlight === $registration->id && $registration->status === 'dokumen_ok',
                            'bg-blue-50/80 dark:bg-blue-900/10' => $highlight === $registration->id && $registration->status === 'terjadwal',
                            'hover:bg-zinc-50 dark:hover:bg-zinc-800/40' => $highlight !== $registration->id,
                        ])>
                            <td class="px-4 py-4">
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $registration->user->name }}</p>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $registration->user->nim ?: 'NIM belum ada' }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                                <p>{{ $registration->user->program_studi ?: 'Program studi belum ada' }}</p>
                                <p class="mt-1 text-zinc-500 dark:text-zinc-400">{{ $registration->scheme?->name ?: 'Skema belum dipilih' }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                                <span @class([
                                    'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset',
                                    'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800/70' => $registration->status === 'dokumen_ok',
                                    'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-800/70' => $registration->status === 'terjadwal',
                                ])>
                                    {{ $registration->status === 'terjadwal' ? 'Terjadwal' : 'Dokumen OK' }}
                                </span>
                                <div class="mt-3 space-y-1">
                                    <p>{{ $registration->exam_date?->translatedFormat('l, d F Y') ?: 'Belum dijadwalkan' }}</p>
                                    @if ($registration->exam_date)
                                        <p class="text-zinc-500 dark:text-zinc-400">Jam {{ $registration->exam_date->format('H:i') }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                                <p>{{ $registration->exam_location ?: 'Lokasi belum diisi' }}</p>
                                <p class="mt-1 text-zinc-500 dark:text-zinc-400">{{ $registration->assessor_name ?: 'Asesor belum diisi' }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    @if ($registration->status === 'terjadwal')
                                        <button wire:click="openScheduleModal({{ $registration->id }})" class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                            Edit
                                        </button>
                                        <button wire:click="confirmDelete({{ $registration->id }})" class="inline-flex items-center gap-2 rounded-xl border border-red-200 px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-900/40 dark:text-red-300 dark:hover:bg-red-950/30">
                                            Hapus
                                        </button>
                                    @else
                                        <button wire:click="openScheduleModal({{ $registration->id }})" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 dark:bg-emerald-500 dark:text-zinc-900 dark:hover:bg-emerald-400">
                                            Jadwalkan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                Tidak ada peserta yang bisa dijadwalkan untuk filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail.id === 'modal-jadwal') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-jadwal') show = false"
        x-on:keydown.escape.window="show = false"
        x-show="show"
        class="relative z-50"
        aria-labelledby="modal-jadwal-title"
        role="dialog"
        aria-modal="true"
        style="display: none;">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-zinc-500/75 dark:bg-black/75"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all dark:bg-zinc-900 sm:my-8 sm:max-w-2xl">
                    <form wire:submit="saveSchedule" class="flex h-full flex-col">
                        <div class="space-y-6 px-6 py-6">
                            <div>
                                <h3 id="modal-jadwal-title" class="text-lg font-semibold text-zinc-900 dark:text-white">
                                    {{ $selectedScheduleRegistration?->status === 'terjadwal' ? 'Edit Jadwal Uji' : 'Buat Jadwal Uji' }}
                                </h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $selectedScheduleRegistration?->user?->name ?: 'Peserta belum dipilih' }}
                                </p>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-900 dark:text-white">Tanggal Uji</label>
                                    <input wire:model="examDate" type="date" class="block w-full rounded-xl border-0 py-2 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-zinc-900 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:focus:ring-white sm:text-sm" />
                                    @error('examDate')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-900 dark:text-white">Jam Uji</label>
                                    <input wire:model="examTime" type="time" class="block w-full rounded-xl border-0 py-2 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-zinc-900 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:focus:ring-white sm:text-sm" />
                                    @error('examTime')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-900 dark:text-white">Lokasi Uji</label>
                                    <input wire:model="examLocation" type="text" class="block w-full rounded-xl border-0 py-2 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-zinc-900 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:focus:ring-white sm:text-sm" />
                                    @error('examLocation')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-zinc-900 dark:text-white">Nama Asesor</label>
                                    <input wire:model="assessorName" type="text" class="block w-full rounded-xl border-0 py-2 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 focus:ring-2 focus:ring-zinc-900 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:focus:ring-white sm:text-sm" />
                                    @error('assessorName')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4 dark:border-zinc-800 dark:bg-zinc-800/50">
                            <button type="button" @click="show = false" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700 dark:bg-emerald-500 dark:text-zinc-900 dark:hover:bg-emerald-400">
                                                Simpan Jadwal
                                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail.id === 'modal-hapus-jadwal') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-hapus-jadwal') show = false"
        x-on:keydown.escape.window="show = false"
        x-show="show"
        class="relative z-50"
        aria-labelledby="modal-hapus-title"
        role="dialog"
        aria-modal="true"
        style="display: none;">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-zinc-500/75 dark:bg-black/75"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all dark:bg-zinc-900 sm:my-8 sm:max-w-lg">
                    <div class="space-y-4 px-6 py-6">
                        <div>
                            <h3 id="modal-hapus-title" class="text-lg font-semibold text-zinc-900 dark:text-white">Hapus Jadwal Uji</h3>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                Jadwal untuk {{ $selectedDeleteRegistration?->user?->name ?: 'peserta ini' }} akan dihapus dan status peserta kembali ke Dokumen OK.
                            </p>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="show = false" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                Batal
                            </button>
                            <button wire:click="deleteSchedule" type="button" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500">
                                Hapus Jadwal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
