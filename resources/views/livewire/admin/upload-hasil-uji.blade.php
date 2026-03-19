<div class="space-y-8 p-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Upload Sertifikat & Hasil Ujian</h1>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Unggah atau perbarui sertifikat dan hasil ujian PDF peserta tanpa menghilangkan datanya dari daftar.</p>
        </div>
    </div>

    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Filter Peserta</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Cari berdasarkan nama atau NIM, lalu sempitkan hasil berdasarkan tanggal ujian.</p>
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
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Peserta Upload Sertifikat</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Peserta yang sudah diunggah tetap ditampilkan agar file bisa diperbarui kapan saja.</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800/70">
                {{ $uploadableRegistrations->count() }} peserta
            </span>
        </div>

        <div class="mt-6 overflow-x-auto rounded-2xl border border-zinc-200 dark:border-zinc-800">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Peserta</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Jadwal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Lokasi / Asesor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Berkas Upload</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-800 dark:bg-zinc-900">
                    @forelse ($uploadableRegistrations as $registration)
                        <tr wire:key="upload-{{ $registration->id }}" @class([
                            'bg-emerald-50/80 dark:bg-emerald-900/10' => $highlight === $registration->id,
                            'hover:bg-zinc-50 dark:hover:bg-zinc-800/40' => $highlight !== $registration->id,
                        ])>
                            <td class="px-4 py-4">
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $registration->user->name }}</p>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $registration->user->nim ?: 'NIM belum ada' }}</p>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $registration->scheme?->name ?: 'Skema belum dipilih' }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                                <div class="space-y-1">
                                    <p>{{ $registration->exam_date?->translatedFormat('l, d F Y') ?: 'Belum ditentukan' }}</p>
                                    @if ($registration->exam_date)
                                        <p class="text-zinc-500 dark:text-zinc-400">Jam {{ $registration->exam_date->format('H:i') }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                                <p>{{ $registration->exam_location ?: 'Lokasi belum diisi' }}</p>
                                <p class="mt-1 text-zinc-500 dark:text-zinc-400">{{ $registration->assessor_name ?: 'Asesor belum diisi' }}</p>
                            </td>
                            <td class="px-4 py-4 text-sm text-zinc-700 dark:text-zinc-300">
                                <div class="flex flex-col items-start gap-2">
                                    <span @class([
                                        'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset',
                                        'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-800/70' => $registration->active_certificate_id === null,
                                        'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800/70' => $registration->active_certificate_id !== null,
                                    ])>
                                        {{ $registration->active_certificate_id !== null ? 'Sudah upload' : 'Belum upload' }}
                                    </span>

                                    @if ($registration->certificate_file_url)
                                        <a href="{{ $registration->certificate_file_url }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                                            Lihat Sertifikat
                                        </a>
                                    @endif

                                    @if ($registration->result_file_url)
                                        <a href="{{ $registration->result_file_url }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-semibold text-zinc-700 transition hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white">
                                            Lihat Hasil Ujian
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-2">
                                    @if ($registration->active_certificate_id !== null)
                                        <button wire:click="openUploadModal({{ $registration->id }})" class="inline-flex items-center gap-2 rounded-xl border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                            Edit
                                        </button>
                                        <button wire:click="confirmDelete({{ $registration->id }})" class="inline-flex items-center gap-2 rounded-xl border border-red-200 px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-900/40 dark:text-red-300 dark:hover:bg-red-950/30">
                                            Hapus
                                        </button>
                                    @else
                                        <button wire:click="openUploadModal({{ $registration->id }})" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                            Upload
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                Tidak ada peserta upload yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail.id === 'modal-upload-sertifikat') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-upload-sertifikat') show = false"
        x-on:keydown.escape.window="show = false"
        x-show="show"
        class="relative z-50"
        aria-labelledby="modal-upload-title"
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
                    <form wire:submit="uploadParticipantFiles" class="flex h-full flex-col">
                        <div class="space-y-6 px-6 py-6">
                            <div>
                                <h3 id="modal-upload-title" class="text-lg font-semibold text-zinc-900 dark:text-white">
                                    {{ $selectedUploadRegistration?->active_certificate_id ? 'Edit Upload Sertifikat & Hasil Ujian' : 'Upload Sertifikat & Hasil Ujian' }}
                                </h3>
                                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $selectedUploadRegistration?->user?->name ?: 'Peserta belum dipilih' }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-900 dark:text-white">File Sertifikat (PDF)</label>
                                <input wire:model="certificateFile" type="file" accept=".pdf,application/pdf" class="block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-3 file:py-2 file:font-semibold file:text-white dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:file:bg-white dark:file:text-zinc-900" />
                                @error('certificateFile')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-zinc-900 dark:text-white">File Hasil Ujian (PDF)</label>
                                <input wire:model="resultFile" type="file" accept=".pdf,application/pdf" class="block w-full rounded-xl border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700 file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-3 file:py-2 file:font-semibold file:text-white dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200 dark:file:bg-white dark:file:text-zinc-900" />
                                @error('resultFile')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/50 dark:bg-emerald-950/30 dark:text-emerald-200">
                                Setelah disimpan, file terbaru tetap tersedia pada dashboard user dan peserta tetap muncul di daftar admin.
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4 dark:border-zinc-800 dark:bg-zinc-800/50">
                            <button type="button" @click="show = false" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                Batal
                            </button>
                            <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                Upload File
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail.id === 'modal-hapus-upload') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-hapus-upload') show = false"
        x-on:keydown.escape.window="show = false"
        x-show="show"
        class="relative z-50"
        aria-labelledby="modal-hapus-upload-title"
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
                            <h3 id="modal-hapus-upload-title" class="text-lg font-semibold text-zinc-900 dark:text-white">Hapus Hasil Upload</h3>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                File upload untuk {{ $selectedDeleteRegistration?->user?->name ?: 'peserta ini' }} akan dihapus dan status peserta kembali ke terjadwal.
                            </p>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="show = false" class="rounded-xl border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                Batal
                            </button>
                            <button wire:click="deleteUploadedFiles" type="button" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500">
                                Hapus Upload
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
