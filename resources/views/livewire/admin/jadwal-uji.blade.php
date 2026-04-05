<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Jadwal Uji</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola peserta yang siap dijadwalkan dan yang sudah memiliki jadwal.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span
                class="inline-flex items-center rounded-full bg-slate-100 px-3.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                {{ $registrations->count() }} Total
            </span>
            <span
                class="inline-flex items-center rounded-full bg-emerald-50 px-3.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">
                {{ $readyRegistrationsCount }} Lunas
            </span>
            <span
                class="inline-flex items-center rounded-full bg-amber-50 px-3.5 py-1 text-xs font-semibold text-amber-700 border border-amber-100">
                {{ $scheduledRegistrationsCount }} Terjadwal
            </span>
        </div>
    </div>

    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Filter Pencarian</h3>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                <div class="relative flex-1 lg:w-72">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Nama / NIM / NIK..."
                        class="block w-full px-4 py-3 pl-10 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                </div>

                <div class="flex-1 lg:w-56">
                    <input wire:model.live="filterDate" type="date"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="border-b border-slate-100 px-6 py-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Link WhatsApp Global</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Kelola satu link WhatsApp universal yang akan dipakai untuk semua peserta terjadwal.
                    </p>
                </div>
                @if ($whatsappSettings->isEmpty())
                    <button type="button" wire:click="openWhatsappLinkModal"
                        class="inline-flex items-center justify-center rounded-2xl bg-emerald-400 px-5 py-3 text-sm font-bold text-black shadow-lg shadow-emerald-500/20 transition-all hover:bg-emerald-500">
                        Tambah Link
                    </button>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b border-gray-50 bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Nama
                            Pengaturan</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Link
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($whatsappSettings as $setting)
                        <tr wire:key="whatsapp-setting-{{ $setting->id }}">
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-gray-900">Link WhatsApp Universal</p>
                                <p class="mt-1 text-xs text-gray-500">Dipakai untuk semua peserta terjadwal</p>
                            </td>
                            <td class="px-6 py-5">
                                <a href="{{ $setting->value }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                    Buka Link
                                </a>
                                <p class="mt-2 break-all text-xs text-gray-500">{{ $setting->value }}</p>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="openWhatsappLinkModal({{ $setting->id }})"
                                        class="text-sm font-semibold text-emerald-600 transition-colors hover:text-emerald-700">
                                        Edit
                                    </button>
                                    <button type="button" wire:click="deleteWhatsappLink({{ $setting->id }})"
                                        class="text-sm font-semibold text-red-600 transition-colors hover:text-red-700">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div
                                    class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-50 text-slate-400">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0H7" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-base font-bold text-gray-900">Belum ada link WhatsApp</h3>
                                <p class="mt-1 text-sm text-gray-500">Simpan link universal agar peserta terjadwal
                                    bisa langsung mengaksesnya.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- WhatsApp Modal --}}
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === 'modal-whatsapp-link') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-whatsapp-link') show = false"
        x-on:keydown.escape.window="show = false" x-show="show" class="relative z-50" style="display: none;">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/40"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95" @click.outside="show = false"
                    class="relative w-full overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-xl">
                    <form wire:submit="saveWhatsappLink" class="flex flex-col">
                        <div class="p-6 md:p-8">
                            <div class="mb-6 flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $editingWhatsappSettingId ? 'Edit Link WhatsApp' : 'Tambah Link WhatsApp' }}
                                    </h3>
                                    <p class="mt-1 text-sm font-medium text-gray-500">
                                        Link ini akan dipakai bersama untuk semua peserta yang sudah dijadwalkan.
                                    </p>
                                </div>
                                <button type="button" @click="show = false; $wire.resetWhatsappLinkForm()"
                                    class="text-gray-400 transition-colors hover:text-gray-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div>
                                <label
                                    class="mb-2 block text-xs font-semibold uppercase tracking-wider text-gray-500">Link
                                    Saluran / Grup WhatsApp</label>
                                <input wire:model="whatsappLink" type="url" placeholder="https://chat.whatsapp.com/..."
                                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white" />
                                @error('whatsappLink')
                                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 border-t border-slate-100 bg-slate-50/50 px-6 py-5 md:px-8">
                            <button type="button" wire:click="resetWhatsappLinkForm" @click="show = false"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="group relative inline-flex items-center justify-center rounded-2xl bg-emerald-400 px-8 py-3.5 font-bold text-black transition-all shadow-lg shadow-emerald-500/20 hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-50">
                                <span wire:loading.remove>{{ $editingWhatsappSettingId ? 'Update Link' : 'Simpan Link' }}</span>
                                <span wire:loading>Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Peserta</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Prodi / Skema
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">
                            Status / Jadwal</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Lokasi /
                            Asesor</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Link
                            WhatsApp</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($registrations as $registration)
                        <tr wire:key="registration-{{ $registration->id }}" @class([
                            'bg-emerald-50/20' =>
                                $highlight === $registration->id &&
                                $registration->status === 'paid',
                            'bg-blue-50/20' =>
                                $highlight === $registration->id &&
                                $registration->status === 'terjadwal',
                            'hover:bg-gray-50/30 transition-colors' => $highlight !== $registration->id,
                        ])>
                            <td class="px-6 py-5">
                                <div class="text-sm font-semibold text-gray-900 leading-none">
                                    {{ $registration->user->name }}</div>
                                <p class="mt-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    {{ $registration->user->isGeneralUser() ? ($registration->user->no_ktp ?: '-') : ($registration->user->nim ?: '-') }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-gray-600 leading-none">
                                    {{ $registration->user->program_studi ?: '-' }}</p>
                                <p class="mt-2 text-xs text-gray-500">{{ $registration->scheme?->name ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span @class([
                                    'inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold',
                                    'bg-emerald-50 text-emerald-700 border-emerald-100' =>
                                        empty($registration->exam_date),
                                    'bg-blue-50 text-blue-700 border-blue-100' =>
                                        !empty($registration->exam_date),
                                ])>
                                    {{ !empty($registration->exam_date) ? 'Terjadwal' : 'Pembayaran Valid' }}
                                </span>
                                <div class="mt-2.5 flex flex-col items-center gap-1.5">
                                    <span
                                        class="text-xs font-semibold text-gray-900">{{ $registration->exam_date?->translatedFormat('d M Y') ?: '-' }}</span>
                                    @if ($registration->exam_date)
                                        <span
                                            class="text-xs text-gray-500 font-medium tracking-tight">{{ $registration->exam_date->format('H:i') }}
                                            WIB</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-semibold text-gray-600 leading-none">
                                    {{ $registration->exam_location ?: '-' }}</p>
                                <p class="mt-2 text-xs text-gray-500">{{ $registration->assessor_name ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-5">
                                @if ($whatsappSettings->isNotEmpty())
                                    <a href="{{ $globalWhatsappLink }}" target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                        Buka Link
                                    </a>
                                @else
                                    <span class="text-sm font-medium text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    @if (!empty($registration->exam_date))
                                        <button wire:click="openScheduleModal({{ $registration->id }})"
                                            class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors mr-3">
                                            Edit
                                        </button>
                                        <button wire:click="confirmDelete({{ $registration->id }})"
                                            class="text-sm font-semibold text-red-600 hover:text-red-700 transition-colors">
                                            Hapus
                                        </button>
                                    @else
                                        <button wire:click="openScheduleModal({{ $registration->id }})"
                                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-semibold text-black hover:bg-emerald-500">
                                            Jadwalkan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400 mx-auto">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-base font-bold text-gray-900">Tidak ada peserta</h3>
                                <p class="mt-1 text-sm text-gray-500">Gunakan filter berbeda atau tunggu pendaftaran
                                    baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Schedule Modal --}}
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === 'modal-jadwal') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-jadwal') show = false"
        x-on:keydown.escape.window="show = false" x-show="show" class="relative z-50" style="display: none;">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/40"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95" @click.outside="show = false"
                    class="relative w-full overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-xl">
                    <form wire:submit="saveSchedule" class="flex flex-col">
                        <div class="p-6 md:p-8">
                            <div class="mb-6 flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $selectedScheduleRegistration?->status === 'terjadwal' ? 'Edit Jadwal Uji' : 'Atur Jadwal Uji' }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 font-medium">
                                        {{ $selectedScheduleRegistration?->user?->name ?: '-' }}
                                    </p>
                                </div>
                                <button type="button" @click="show = false"
                                    class="text-gray-400 hover:text-gray-500 transition-colors">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-6">
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Tanggal
                                            Uji</label>
                                        <input wire:model="examDate" type="date"
                                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white" />
                                        @error('examDate')
                                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Waktu
                                            Uji</label>
                                        <input wire:model="examTime" type="time"
                                            class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white" />
                                        @error('examTime')
                                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Lokasi
                                        Tempat Uji</label>
                                    <input wire:model="examLocation" type="text"
                                        placeholder="Gedung Ki Hajar Dewantara Lt. 3"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none" />
                                    @error('examLocation')
                                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">Nama
                                        Penguji</label>
                                    <input wire:model="assessorName" type="text"
                                        class="block w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white"
                                        placeholder="Contoh: Dr. John Doe, M.Kom" />
                                    @error('assessorName')
                                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 bg-slate-50/50 px-6 py-5 md:px-8 border-t border-slate-100">
                            <button type="button" @click="show = false"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-black bg-emerald-400 rounded-2xl hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-500/20 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove>Simpan Jadwal</span>
                                <span wire:loading>Menyimpan...</span>
                                <svg wire:loading.remove
                                    class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7-7 7M3 12h18" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === 'modal-hapus-jadwal') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-hapus-jadwal') show = false"
        x-on:keydown.escape.window="show = false" x-show="show" class="relative z-50" style="display: none;">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full overflow-hidden rounded-[1.25rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-lg">
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
                                <h3 class="text-xl font-bold text-gray-900">Hapus Jadwal Uji</h3>
                                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                                    Jadwal untuk <span
                                        class="font-bold text-gray-800">{{ $selectedDeleteRegistration?->user?->name ?: 'peserta ini' }}</span>
                                    akan dihapus dan status peserta otomatis dikembalikan menjadi Pembayaran Tervalidasi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 flex items-center justify-end gap-3 px-6 py-4 md:px-8">
                        <button type="button" @click="show = false"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button wire:click="deleteSchedule" type="button"
                            class="rounded-xl bg-red-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-100 transition-all hover:bg-red-700">
                            Hapus Jadwal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
