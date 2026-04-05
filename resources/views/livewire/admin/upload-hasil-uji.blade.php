<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Upload Hasil Uji</h1>
            <p class="mt-1 text-sm text-gray-500">Unggah sertifikat dan hasil ujian PDF peserta yang telah selesai ujian.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span
                class="inline-flex items-center rounded-full bg-slate-100 px-3.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                {{ $uploadableRegistrations->count() }} Peserta Terkait
            </span>
        </div>
    </div>

    <div class="rounded-[1.25rem] bg-white p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] border border-slate-100">
        <div class="flex flex-col md:flex-row items-center gap-4">
            <div
                class="hidden lg:flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
            </div>

            <div class="flex flex-col sm:flex-row flex-1 gap-4 w-full">
                <div class="relative flex-1 group">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 transition-colors group-focus-within:text-emerald-500">
                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / NIM / NIK..."
                        class="block w-full px-4 py-3 pl-10 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                </div>

                <div class="flex-1 sm:w-48 lg:w-56 group relative">
                    <select wire:model.live="filterStatus"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all appearance-none hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                        <option value="">Semua Status</option>
                        <option value="kompeten">Lulus (Kompeten)</option>
                        <option value="belum_kompeten">Tidak Lolos</option>
                        <option value="belum_upload">Belum Upload</option>
                    </select>
                    <div
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 group-focus-within:text-emerald-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <div class="flex-1 sm:w-48 group">
                    <input wire:model.live="filterDate" type="date"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                </div>

                @if ($search || $filterStatus || $filterDate)
                    <button wire:click="resetFilters" type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition-all hover:bg-slate-50 hover:text-red-600 hover:border-red-100 shadow-sm group whitespace-nowrap">
                        <svg class="h-4 w-4 transition-transform group-hover:rotate-180 duration-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Peserta</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">
                            Jadwal</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">
                            Status</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">Berkas</th>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($uploadableRegistrations as $registration)
                        <tr wire:key="upload-{{ $registration->id }}" @class([
                            'bg-emerald-50/20' => $highlight === $registration->id,
                            'hover:bg-gray-50/30 transition-colors' => $highlight !== $registration->id,
                        ])>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-900 leading-none">
                                        {{ $registration->user->name }}</span>
                                    @if ($registration->type === 'perpanjangan')
                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-bold text-blue-600 border border-blue-100">Perpanjangan</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    {{ $registration->user->isGeneralUser() ? ($registration->user->no_ktp ?: '-') : ($registration->user->nim ?: '-') }} • {{ $registration->scheme?->name ?: '-' }}
                                </p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span
                                        class="text-xs font-semibold text-gray-900">{{ $registration->exam_date?->translatedFormat('d M Y') ?: '-' }}</span>
                                    @if ($registration->exam_date)
                                        <span
                                            class="text-xs text-gray-500 font-medium tracking-tight">{{ $registration->exam_date->format('H:i') }}
                                            WIB</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @php
                                    $statusClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                    $statusLabel = 'Belum Upload';

                                    if ($registration->status === 'tidak_kompeten') {
                                        $statusClass = 'bg-red-50 text-red-700 border-red-100';
                                        $statusLabel = 'Tidak Lolos';
                                    } elseif ($registration->active_certificate_id !== null) {
                                        $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                        $statusLabel = 'Kompeten';
                                    }
                                @endphp
                                <span
                                    class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-2">
                                    @if ($registration->certificate_file_url)
                                        <a href="{{ $registration->certificate_file_url }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Sertifikat PDF
                                        </a>
                                    @endif

                                    @if ($registration->result_file_url)
                                        <a href="{{ $registration->result_file_url }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Hasil Ujian PDF
                                        </a>
                                    @endif

                                    @if (!$registration->certificate_file_url && !$registration->result_file_url)
                                        <span
                                            class="text-xs font-medium text-gray-400 italic font-medium tracking-tight">Belum
                                            ada file</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                <div class="flex justify-end items-center gap-2">
                                    @if (in_array($registration->status, ['sertifikat_terbit', 'tidak_kompeten']))
                                        <button wire:click="openUploadModal({{ $registration->id }})"
                                            class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition-colors mr-3">
                                            Edit
                                        </button>
                                        <button wire:click="confirmDelete({{ $registration->id }})"
                                            class="text-xs font-semibold text-red-600 hover:text-red-700 transition-colors">
                                            Hapus
                                        </button>
                                    @else
                                        @if ($registration->type === 'perpanjangan')
                                            <button wire:click="openUploadModal({{ $registration->id }})"
                                                class="inline-flex items-center gap-1.5 rounded-xl bg-blue-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-600 transition-colors shadow-sm">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Perpanjangan
                                            </button>
                                        @else
                                            <button wire:click="openUploadModal({{ $registration->id }})"
                                                class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-semibold text-black hover:bg-emerald-500 transition-colors shadow-sm">
                                                Upload Sekarang
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400 mx-auto">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-base font-bold text-gray-900">Belum ada data upload</h3>
                                <p class="mt-1 text-sm text-gray-500 font-medium">Peserta yang terjadwal akan muncul di
                                    sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Upload Modal --}}
    <div x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail.id === 'modal-upload-sertifikat') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-upload-sertifikat') show = false"
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
                    class="relative w-full overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-2xl">
                    <form wire:submit="uploadParticipantFiles" class="flex flex-col">
                        <div class="p-6 md:p-8">
                            <div class="mb-6 flex items-start justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $selectedUploadRegistration?->active_certificate_id ? 'Perbarui Hasil Ujian' : 'Upload Hasil Ujian' }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 font-medium">
                                        {{ $selectedUploadRegistration?->user?->name ?: '-' }}
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
                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">Keputusan
                                        Hasil Ujian</label>
                                    <select wire:model.live="examResult"
                                        class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                                        <option value="kompeten">Lulus (Kompeten)</option>
                                        <option value="belum_kompeten">Tidak Lolos (Belum Kompeten)</option>
                                    </select>
                                    @error('examResult')
                                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if ($examResult === 'kompeten')
                                    <div class="grid gap-5 md:grid-cols-2">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">File
                                                Sertifikat (PDF)</label>
                                            <div class="relative">
                                                <input wire:model="certificateFile" type="file" accept=".pdf"
                                                    class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white file:mr-3.5 file:rounded-lg file:border-0 file:bg-gray-900 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white" />
                                            </div>
                                            @if ($selectedUploadRegistration?->active_certificate_id)
                                                <p class="mt-2 text-xs text-gray-400 italic">Kosongkan jika tidak
                                                    ada perubahan</p>
                                            @endif
                                            @error('certificateFile')
                                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">Masa
                                                Berlaku</label>
                                            <input wire:model="expiredDate" type="date"
                                                class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-semibold text-gray-900 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white" />
                                            @error('expiredDate')
                                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2.5">File
                                        Detail Hasil Ujian (PDF)</label>
                                    <input wire:model="resultFile" type="file" accept=".pdf"
                                        class="block w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-700 outline-none transition-all hover:bg-white hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white file:mr-3.5 file:rounded-lg file:border-0 file:bg-gray-900 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white" />
                                    @if (
                                        ($examResult === 'kompeten' && $selectedUploadRegistration?->active_certificate_id) ||
                                            ($examResult === 'belum_kompeten' && $selectedUploadRegistration?->exam_result_path))
                                        <p class="mt-2 text-xs text-gray-400 italic">Kosongkan jika tidak ada
                                            perubahan</p>
                                    @endif
                                    @error('resultFile')
                                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-4">
                                    <div class="flex gap-3">
                                        <svg class="h-5 w-5 text-blue-500 shrink-0 mt-0.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-xs leading-relaxed text-blue-700 font-medium">
                                            @if ($selectedUploadRegistration?->active_certificate_id)
                                                <span class="font-bold">Informasi:</span> Anda sedang memperbarui
                                                berkas. Berkas lama tetap aman jika tidak diunggah ulang.
                                            @else
                                                <span class="font-bold">Penting:</span> Peserta akan langsung dapat
                                                melihat status
                                                {{ $examResult === 'kompeten' ? 'Lulus' : 'Tidak Lolos' }} di dashboard
                                                mereka setelah Anda menekan tombol Simpan.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 bg-slate-50/50 px-6 py-5 md:px-8 border-t border-slate-100">
                            <button type="button" @click="show = false"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-black bg-emerald-400 rounded-2xl hover:bg-emerald-500">
                                <span>Simpan Berkas</span>
                                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === 'modal-hapus-upload') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-hapus-upload') show = false"
        x-on:keydown.escape.window="show = false" x-show="show" class="relative z-50" style="display: none;">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/40"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.outside="show = false"
                    class="relative overflow-hidden rounded-[2rem] w-full bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-lg">
                    <div class="p-6 md:p-8">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Hapus Hasil Upload</h3>
                                <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                                    Berkas hasil ujian dan sertifikat untuk <span
                                        class="font-bold text-gray-800">{{ $selectedDeleteRegistration?->user?->name ?: 'peserta ini' }}</span>
                                    akan dihapus permanen. Status peserta akan kembali menjadi terjadwal.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end gap-4 bg-slate-50/50 px-6 py-5 md:px-8 border-t border-slate-100">
                        <button type="button" @click="show = false"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </button>
                        <button type="button" wire:click="deleteUploadedFiles" @click="show = false"
                            class="group relative inline-flex items-center justify-center px-8 py-3.5 font-bold text-white bg-red-600 rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20">
                            <span>Ya, Hapus Hasil</span>
                            <svg class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
