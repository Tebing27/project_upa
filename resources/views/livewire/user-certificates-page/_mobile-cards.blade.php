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
