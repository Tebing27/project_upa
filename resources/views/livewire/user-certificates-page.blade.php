<div class="space-y-8 bg-slate-50/50 p-6 min-h-screen lg:px-8" x-data="{ certificateMissingOpen: false }"
    x-on:certificate-missing.window="certificateMissingOpen = true">
    @php
        $downloadUrl = $activeCertificate?->file_path ? Storage::url($activeCertificate->file_path) : null;
        $resultDownloadUrl = $activeCertificate?->result_file_path
            ? Storage::url($activeCertificate->result_file_path)
            : null;
    @endphp

    <div>
        <h1 class="text-[1.75rem] font-bold text-gray-900">Sertifikat</h1>
        <p class="mt-1.5 text-sm text-gray-500">Lihat sertifikat aktif dan riwayat sertifikat yang sudah diterbitkan.</p>
    </div>

    @if ($pendingCompetencyRegistrations->isNotEmpty())
        <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="border-b border-gray-100 px-6 py-5 md:px-8">
                <h2 class="text-[1.15rem] font-bold text-gray-900">Status Kompeten Menunggu Sertifikat Copy</h2>
                <p class="mt-1 text-sm text-gray-500">Surat keterangan sudah bisa diunduh lebih dulu. Sertifikat copy akan muncul setelah admin mengunggahnya.</p>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach ($pendingCompetencyRegistrations as $registration)
                    <div class="flex flex-col gap-4 px-6 py-5 md:px-8 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $registration->scheme?->nama ?? '-' }}</p>
                            <p class="mt-1 text-[13px] text-gray-500">
                                Diputuskan kompeten
                                @if ($registration->updated_at)
                                    pada {{ $registration->updated_at->translatedFormat('d F Y') }}
                                @endif
                            </p>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row w-full lg:w-auto">
                            @if ($registration->exam?->exam_result_path)
                                <a href="{{ Storage::url($registration->exam->exam_result_path) }}" target="_blank"
                                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-emerald-400 px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-emerald-500">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16" />
                                    </svg>
                                    Unduh Surat Keterangan
                                </a>
                            @elseif ($hasCompetencyLetterAssets)
                                <button wire:click="downloadCompetencyLetter({{ $registration->id }})"
                                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-emerald-400 px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-emerald-500">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16" />
                                    </svg>
                                    <span wire:loading.remove wire:target="downloadCompetencyLetter({{ $registration->id }})">Unduh Surat Keterangan</span>
                                    <span wire:loading wire:target="downloadCompetencyLetter({{ $registration->id }})">Memproses...</span>
                                </button>
                            @else
                                <button disabled
                                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-400">
                                    Surat keterangan belum tersedia
                                </button>
                            @endif

                            <button type="button" x-on:click="$dispatch('certificate-missing')"
                                class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[13px] sm:text-sm font-semibold text-slate-600 text-center transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700">
                                Unduh Sertifikat
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="border-b border-gray-100 px-6 py-5 md:px-8 flex flex-col gap-5">
            <h2 class="text-[1.35rem] font-bold text-gray-900">Semua Sertifikat</h2>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div
                    class="inline-flex w-full overflow-x-auto sm:w-auto items-center p-1.5 bg-gray-100 rounded-xl shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)] [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                    <label class="relative cursor-pointer shrink-0">
                        <input type="radio" wire:model.live="filterStatus" value="" class="peer sr-only" />
                        <span
                            class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-1.5 text-sm font-medium transition-all duration-200 rounded-lg text-gray-500 hover:text-gray-900 peer-checked:bg-white peer-checked:text-gray-900 peer-checked:shadow-sm">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            Semua
                        </span>
                    </label>

                    <label class="relative cursor-pointer shrink-0">
                        <input type="radio" wire:model.live="filterStatus" value="active" class="peer sr-only" />
                        <span
                            class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-1.5 text-sm font-medium transition-all duration-200 rounded-lg text-gray-500 hover:text-[#1b8a6b] peer-checked:bg-white peer-checked:text-[#1b8a6b] peer-checked:shadow-sm">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Aktif
                        </span>
                    </label>

                    <label class="relative cursor-pointer shrink-0">
                        <input type="radio" wire:model.live="filterStatus" value="inactive" class="peer sr-only" />
                        <span
                            class="whitespace-nowrap inline-flex items-center gap-2 px-4 py-1.5 text-sm font-medium transition-all duration-200 rounded-lg text-gray-500 hover:text-gray-900 peer-checked:bg-white peer-checked:text-gray-900 peer-checked:shadow-sm">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tidak Aktif
                        </span>
                    </label>
                </div>

                @if ($faculties->isNotEmpty())
                    <div class="w-full sm:w-64">
                        <select wire:model.live="filterFaculty"
                            class="block w-full rounded-xl border border-gray-200 bg-white py-2 pl-4 pr-10 text-sm font-medium text-gray-700 shadow-sm transition-all focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Kategori Fakultas</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty }}">{{ $faculty }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto pb-4">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 md:px-8">
                            Skema</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">No.
                            Sertifikat</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            Tanggal Terbit</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            Masa Berlaku</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500">
                            Status</th>
                        <th
                            class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 md:px-8">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($certificates as $certificate)
                        <tr class="transition-colors hover:bg-gray-50/50">
                            <td class="px-6 py-4 md:px-8">
                                <p class="font-semibold text-gray-900">{{ $certificate->scheme_name }}</p>
                                <p class="mt-1 text-[12px] text-gray-500">
                                    {{ $certificate->scheme?->faculty ?? 'Umum' }}
                                    @if ($certificate->scheme?->study_program)
                                        - {{ $certificate->scheme->study_program }}
                                    @endif
                                </p>
                            </td>
                            <td class="px-6 py-4 font-mono text-[13px] text-gray-600">
                                {{ $certificate->displayNumber() }}
                            </td>
                            <td class="px-6 py-4 text-[13px] text-gray-600">
                                {{ $certificate->created_at ? $certificate->created_at->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-[13px] text-gray-600">
                                {{ $certificate->expired_date ? 's.d. ' . $certificate->expired_date->translatedFormat('d F Y') : 'Seumur Hidup' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $inProgress = auth()
                                        ->user()
                                        ->hasInProgressRegistrationForScheme($certificate->scheme_id ?? 0);
                                    $hasFailedLatest = auth()
                                        ->user()
                                        ->hasFailedLatestRegistrationForScheme($certificate->scheme_id ?? 0);

                                    if ($certificate->is_active) {
                                        $badgeClasses = 'bg-emerald-50 text-[#1b8a6b] ring-emerald-200/50';
                                        $statusText = 'Aktif';
                                    } elseif ($hasFailedLatest) {
                                        $badgeClasses = 'bg-red-50 text-red-600 ring-red-200/50';
                                        $statusText = 'Tidak Lolos';
                                    } else {
                                        $badgeClasses = 'bg-red-50 text-red-600 ring-red-200/50';
                                        $statusText = 'Kedaluwarsa';
                                    }
                                @endphp
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $badgeClasses }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 md:px-8">
                                <div class="flex flex-col gap-2">
                                    @if ($certificate->is_active)
                                        @if ($certificate->file_path)
                                            <button wire:click="downloadCertificateAsPdf({{ $certificate->id }})"
                                                class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#1b8a6b] transition hover:text-[#157158] text-left mt-1">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                <span wire:loading.remove
                                                    wire:target="downloadCertificateAsPdf({{ $certificate->id }})">Unduh Sertifikat</span>
                                                <span wire:loading
                                                    wire:target="downloadCertificateAsPdf({{ $certificate->id }})">Memproses...</span>
                                            </button>
                                        @else
                                            <button type="button" x-on:click="$dispatch('certificate-missing')"
                                                class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-[#1b8a6b] transition hover:text-[#157158] text-left mt-1">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Unduh Sertifikat
                                            </button>
                                        @endif

                                        @if ($certificate->result_file_path)
                                            <a href="{{ Storage::url($certificate->result_file_path) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-gray-600 transition hover:text-gray-900">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                                                </svg>
                                                Unduh Surat Keterangan
                                            </a>
                                        @endif

                                        @if (!$certificate->file_path && !$certificate->result_file_path)
                                            <span class="text-[13px] text-gray-400">Tidak ada</span>
                                        @endif
                                    @else
                                        <span class="text-[13px] text-gray-400">Tidak ada aksi</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[13px] text-gray-500">
                                Belum ada sertifikat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block lg:hidden border-t border-gray-100 divide-y divide-gray-100">
            @forelse ($certificates as $certificate)
                @php
                    $inProgress = auth()->user()->hasInProgressRegistrationForScheme($certificate->scheme_id ?? 0);
                    $hasFailedLatest = auth()->user()->hasFailedLatestRegistrationForScheme($certificate->scheme_id ?? 0);

                    if ($certificate->is_active) {
                        $badgeClasses = 'bg-emerald-50 text-[#1b8a6b] ring-emerald-200/50';
                        $statusText = 'Aktif';
                    } elseif ($hasFailedLatest) {
                        $badgeClasses = 'bg-red-50 text-red-600 ring-red-200/50';
                        $statusText = 'Tidak Lolos';
                    } else {
                        $badgeClasses = 'bg-red-50 text-red-600 ring-red-200/50';
                        $statusText = 'Kedaluwarsa';
                    }
                @endphp
                <div class="p-6 space-y-4">
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-start justify-between gap-4">
                            <h3 class="font-semibold text-gray-900 leading-tight">{{ $certificate->scheme_name }}</h3>
                            <span class="inline-flex shrink-0 items-center rounded-full px-2.5 py-1 text-[10px] font-semibold ring-1 ring-inset {{ $badgeClasses }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        <p class="text-[12px] text-gray-500">
                            {{ $certificate->scheme?->faculty ?? 'Umum' }}
                            @if ($certificate->scheme?->study_program)
                                - {{ $certificate->scheme->study_program }}
                            @endif
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 rounded-xl bg-gray-50 p-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">No. Sertifikat</p>
                            <p class="mt-1 font-mono text-[12px] font-medium text-gray-900">{{ $certificate->displayNumber() }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Tanggal Terbit</p>
                            <p class="mt-1 text-[12px] font-medium text-gray-900">{{ $certificate->created_at ? $certificate->created_at->translatedFormat('d M Y') : '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Masa Berlaku</p>
                            <p class="mt-1 text-[12px] font-medium text-gray-900">{{ $certificate->expired_date ? 's.d. ' . $certificate->expired_date->translatedFormat('d F Y') : 'Seumur Hidup' }}</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        @if ($certificate->is_active)
                            <div class="flex flex-col sm:flex-row gap-2.5">
                                @if ($certificate->file_path)
                                    <button wire:click="downloadCertificateAsPdf({{ $certificate->id }})" class="inline-flex w-full sm:w-auto items-center justify-center gap-1.5 rounded-xl border border-[#1b8a6b] px-4 py-2 text-[13px] font-semibold text-[#1b8a6b] transition hover:bg-emerald-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <span wire:loading.remove wire:target="downloadCertificateAsPdf({{ $certificate->id }})">Unduh Sertifikat</span>
                                        <span wire:loading wire:target="downloadCertificateAsPdf({{ $certificate->id }})">Memproses...</span>
                                    </button>
                                @else
                                    <button type="button" x-on:click="$dispatch('certificate-missing')" class="inline-flex w-full sm:w-auto items-center justify-center gap-1.5 rounded-xl border border-[#1b8a6b] px-4 py-2 text-[13px] font-semibold text-[#1b8a6b] transition hover:bg-emerald-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Unduh Sertifikat
                                    </button>
                                @endif

                                @if ($certificate->result_file_path)
                                    <a href="{{ Storage::url($certificate->result_file_path) }}" target="_blank" class="inline-flex w-full sm:w-auto items-center justify-center gap-1.5 rounded-xl border border-gray-200 px-4 py-2 text-[13px] font-semibold text-gray-600 transition hover:bg-gray-50 hover:text-gray-900">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                                        </svg>
                                        Unduh Surat Keterangan
                                    </a>
                                @endif

                                @if (!$certificate->file_path && !$certificate->result_file_path)
                                    <span class="block w-full rounded-xl bg-gray-50 px-4 py-2 text-center text-[13px] text-gray-400">Belum ada file</span>
                                @endif
                            </div>
                        @else
                            <span class="block w-full rounded-xl bg-gray-50 px-4 py-2 text-center text-[13px] text-gray-400">Tidak ada aksi</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-[13px] text-gray-500">
                    Belum ada sertifikat.
                </div>
            @endforelse
        </div>
    </div>

    <div x-cloak x-show="certificateMissingOpen" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4">
        <div x-show="certificateMissingOpen" x-transition @click.outside="certificateMissingOpen = false"
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-slate-900">Sertifikat belum tersedia</h3>
            <p class="mt-2 text-sm leading-relaxed text-slate-600">
                Sertifikat copy belum diunggah admin. Silakan gunakan surat keterangan terlebih dahulu.
            </p>
            <div class="mt-5 flex justify-end">
                <button type="button" x-on:click="certificateMissingOpen = false"
                    class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-black transition hover:bg-emerald-400">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
