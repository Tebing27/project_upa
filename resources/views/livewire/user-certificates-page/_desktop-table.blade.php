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
