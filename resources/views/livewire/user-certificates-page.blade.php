<div class="space-y-8 bg-slate-50/50 p-6 min-h-screen lg:px-8">
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

    <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="border-b border-gray-100 px-6 py-5 md:px-8 flex flex-col gap-5">
            <h2 class="text-[1.35rem] font-bold text-gray-900">Semua Sertifikat</h2>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div
                    class="inline-flex w-full sm:w-auto overflow-x-auto items-center p-1.5 bg-gray-100 rounded-xl shadow-[inset_0_1px_2px_rgba(0,0,0,0.05)]">
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

        <div class="overflow-x-auto pb-4">
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
                                    } elseif ($inProgress) {
                                        $badgeClasses = 'bg-amber-50 text-amber-600 ring-amber-200/50';
                                        $statusText = 'Proses Perpanjangan';
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
                                                Unduh Hasil Ujian
                                            </a>
                                        @endif

                                        @if (!$certificate->file_path && !$certificate->result_file_path)
                                            <span class="text-[13px] text-gray-400">Tidak ada</span>
                                        @endif
                                    @else
                                        @if ($inProgress)
                                            <button disabled
                                                class="inline-flex items-center justify-center gap-1.5 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-500 bg-gray-100 cursor-not-allowed">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Proses Perpanjangan
                                            </button>
                                        @else
                                            <a href="{{ route('dashboard.daftar-skema', ['type' => 'perpanjangan', 'scheme' => $certificate->scheme_id ?? 0]) }}"
                                                class="inline-flex items-center justify-center gap-1.5 rounded-xl px-3 py-2.5 text-sm font-semibold text-black bg-emerald-400 hover:bg-emerald-500">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Perpanjangan
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[13px] text-gray-500">
                                Belum ada sertifikat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
