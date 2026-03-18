<div class="space-y-6">
    @php
        $currentStep = $latestRegistration ? $this->getStepProgress($latestRegistration->status) : 1;
        $registrationStatusLabel = $latestRegistration ? $this->getStatusLabel($latestRegistration->status) : 'Belum ada pendaftaran';
        $statusBadgeClasses = match ($latestRegistration?->status) {
            'dokumen_ditolak', 'rejected', 'tidak_kompeten' => 'bg-red-50 text-red-700 ring-red-200 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-800/70',
            'terjadwal', 'selesai_uji', 'kompeten', 'sertifikat_terbit' => 'bg-blue-50 text-blue-700 ring-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-800/70',
            'dokumen_ok', 'menunggu_verifikasi' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800/70',
            'pending_payment' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-800/70',
            default => 'bg-zinc-100 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
        };
        $steps = [
            1 => 'Daftar',
            2 => 'Verifikasi Data & Dokumen',
            3 => 'Jadwal Ujian',
            4 => 'Sertifikat Terbit',
        ];
        $progressHeading = match ($currentStep) {
            2 => 'Dokumen yang perlu diperbaiki',
            3 => 'Jadwal ujian Anda',
            4 => 'Sertifikat aktif',
            default => 'Pendaftaran berhasil dibuat',
        };
        $registrationDate = $latestRegistration?->created_at?->translatedFormat('d F Y');
        $examDate = $latestRegistration?->exam_date?->translatedFormat('l, d F Y H:i');
        $certificateExpiry = $activeCertificate?->expired_date?->translatedFormat('F Y');
        $certificateDownloadUrl = $activeCertificate?->file_path ? Storage::url($activeCertificate->file_path) : null;
        $resultDownloadUrl = $activeCertificate?->result_file_path ? Storage::url($activeCertificate->result_file_path) : null;
    @endphp

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <p class="text-sm font-medium uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Sertifikat Aktif</p>
            <p class="mt-4 text-4xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $activeCertificatesCount }}</p>
            <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                {{ $activeCertificate ? $activeCertificate->scheme_name : 'Tidak ada sertifikat aktif.' }}
            </p>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <p class="text-sm font-medium uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Status Pendaftaran</p>
            <p class="mt-4 text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $registrationStatusLabel }}</p>
            <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                Tahap {{ $currentStep }} dari 4
            </p>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <p class="text-sm font-medium uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">Kode Referensi</p>
            <p class="mt-4 text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $latestRegistration?->payment_reference ?? 'Tidak ada' }}</p>
            <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                {{ $latestRegistration?->va_number ? 'VA: ' . $latestRegistration->va_number : 'Menyesuaikan data pendaftaran terbaru.' }}
            </p>
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <a href="{{ route('dashboard.status') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-2xl border border-zinc-200 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 shadow-sm transition hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:hover:bg-zinc-800">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2" />
            </svg>
            Buka Status Pendaftaran
        </a>
        <a href="{{ route('dashboard.certificates') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-2xl border border-zinc-200 bg-white px-5 py-3 text-sm font-semibold text-zinc-900 shadow-sm transition hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:hover:bg-zinc-800">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            </svg>
            Buka Sertifikat Saya
        </a>
    </div>

    <div class="rounded-[2rem] border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Progress Pendaftaran</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Pantau proses sertifikasi Anda dari pendaftaran hingga sertifikat terbit.</p>
            </div>

            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusBadgeClasses }}">
                {{ $registrationStatusLabel }}
            </span>
        </div>

        <div class="mt-8 overflow-x-auto pb-2">
            <div class="relative mx-auto flex min-w-[700px] justify-between px-1">
                <div class="absolute left-8 right-8 top-5 h-0.5 bg-zinc-200 dark:bg-zinc-700"></div>
                <div class="absolute left-8 top-5 h-0.5 bg-emerald-500 transition-all"
                    style="width: calc((100% - 4rem) * {{ max($currentStep - 1, 0) / 3 }});"></div>

                @foreach ($steps as $stepNumber => $stepLabel)
                    @php
                        $isCompleted = $stepNumber < $currentStep;
                        $isCurrent = $stepNumber === $currentStep;
                        $isRejectedStep = $stepNumber === 2 && in_array($latestRegistration?->status, ['dokumen_ditolak', 'rejected'], true);
                    @endphp

                    <div class="relative z-10 flex w-40 flex-col items-center text-center">
                        <div @class([
                            'flex h-10 w-10 items-center justify-center rounded-full border-2 bg-white text-sm font-semibold dark:bg-zinc-900',
                            'border-emerald-500 bg-emerald-500 text-white dark:bg-emerald-500' => $isCompleted,
                            'border-blue-500 text-blue-600 ring-4 ring-blue-100 dark:text-blue-300 dark:ring-blue-900/40' => $isCurrent && ! $isRejectedStep,
                            'border-red-500 text-red-600 ring-4 ring-red-100 dark:text-red-300 dark:ring-red-900/40' => $isRejectedStep,
                            'border-zinc-300 text-zinc-400 dark:border-zinc-600 dark:text-zinc-500' => ! $isCompleted && ! $isCurrent && ! $isRejectedStep,
                        ])>
                            @if ($isCompleted)
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif ($isRejectedStep)
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @else
                                {{ $stepNumber }}
                            @endif
                        </div>

                        <p class="mt-3 text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $stepLabel }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-zinc-200 bg-zinc-50/80 p-5 dark:border-zinc-800 dark:bg-zinc-800/60">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-zinc-500 dark:text-zinc-400">{{ $progressHeading }}</p>

            @if (! $latestRegistration)
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300">Belum ada data pendaftaran.</p>
            @elseif ($currentStep === 2)
                @if (count($rejectedDocuments) > 0)
                    <div class="mt-4 space-y-3">
                        @foreach ($rejectedDocuments as $document)
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800/70 dark:bg-red-900/20 dark:text-red-300">
                                <p class="font-semibold">{{ $document['label'] }} ditolak</p>
                                <p class="mt-1 text-red-600/90 dark:text-red-300/90">{{ $document['note'] ?: 'Silakan upload ulang dokumen ini.' }}</p>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('dashboard') }}#detail-pendaftaran" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-red-600 transition hover:text-red-700 dark:text-red-300 dark:hover:text-red-200">
                        Lihat status pendaftaran
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300">Data dan dokumen Anda sedang diproses untuk verifikasi admin.</p>
                @endif
            @elseif ($currentStep === 3)
                <div class="mt-4 grid gap-3 text-sm text-zinc-600 dark:text-zinc-300 md:grid-cols-3">
                    <div class="rounded-2xl border border-zinc-200 bg-white px-4 py-3 dark:border-zinc-700 dark:bg-zinc-900/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">Tanggal Ujian</p>
                        <p class="mt-2 font-medium text-zinc-900 dark:text-white">{{ $examDate ?? 'Belum ditentukan' }}</p>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 bg-white px-4 py-3 dark:border-zinc-700 dark:bg-zinc-900/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">Lokasi</p>
                        <p class="mt-2 font-medium text-zinc-900 dark:text-white">{{ $latestRegistration->exam_location ?: 'Belum ditentukan' }}</p>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 bg-white px-4 py-3 dark:border-zinc-700 dark:bg-zinc-900/70">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">Asesor</p>
                        <p class="mt-2 font-medium text-zinc-900 dark:text-white">{{ $latestRegistration->assessor_name ?: 'Belum ditentukan' }}</p>
                    </div>
                </div>
            @elseif ($currentStep === 4)
                <div class="mt-4 flex flex-col gap-4 rounded-[1.75rem] bg-linear-to-br from-sky-950 via-blue-950 to-slate-900 p-6 text-white md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-blue-200/80">Sertifikat Aktif</p>
                        <h3 class="mt-4 text-2xl font-semibold tracking-tight">{{ $activeCertificate?->scheme_name ?? 'Tidak ada' }}</h3>
                        <p class="mt-2 text-sm text-blue-100/80">
                            {{ $activeCertificate?->level ? $activeCertificate->level . ' - ' : '' }}{{ $certificateExpiry ? 'Berlaku s.d. ' . $certificateExpiry : 'Sertifikat belum tersedia.' }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3">
                        @if ($certificateDownloadUrl)
                            <a href="{{ $certificateDownloadUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/12 px-4 py-3 text-sm font-semibold text-white ring-1 ring-inset ring-white/15 transition hover:bg-white/20">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh Sertifikat
                            </a>
                        @else
                            <span class="inline-flex items-center justify-center rounded-xl bg-white/10 px-4 py-3 text-sm font-semibold text-blue-100/90 ring-1 ring-inset ring-white/10">
                                File sertifikat belum tersedia
                            </span>
                        @endif

                        @if ($resultDownloadUrl)
                            <a href="{{ $resultDownloadUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/12 px-4 py-3 text-sm font-semibold text-white ring-1 ring-inset ring-white/15 transition hover:bg-white/20">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                                </svg>
                                Unduh Hasil Ujian
                            </a>
                        @else
                            <span class="inline-flex items-center justify-center rounded-xl bg-white/10 px-4 py-3 text-sm font-semibold text-blue-100/90 ring-1 ring-inset ring-white/10">
                                File hasil ujian belum tersedia
                            </span>
                        @endif
                    </div>
                </div>
            @else
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300">Pendaftaran Anda sudah masuk ke sistem. Langkah berikutnya adalah verifikasi data dan dokumen.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <div id="detail-pendaftaran" class="rounded-[2rem] border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Detail Pendaftaran</h2>

            <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50/80 px-4 py-4 dark:border-zinc-800 dark:bg-zinc-800/60">
                    <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">Nama</dt>
                    <dd class="mt-2 text-sm font-medium text-zinc-900 dark:text-white">{{ auth()->user()->name }}</dd>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50/80 px-4 py-4 dark:border-zinc-800 dark:bg-zinc-800/60">
                    <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">NIM</dt>
                    <dd class="mt-2 text-sm font-medium text-zinc-900 dark:text-white">{{ auth()->user()->nim ?: 'Tidak ada' }}</dd>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50/80 px-4 py-4 dark:border-zinc-800 dark:bg-zinc-800/60">
                    <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">Program Studi</dt>
                    <dd class="mt-2 text-sm font-medium text-zinc-900 dark:text-white">{{ auth()->user()->program_studi ?: 'Tidak ada' }}</dd>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50/80 px-4 py-4 dark:border-zinc-800 dark:bg-zinc-800/60">
                    <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">Skema</dt>
                    <dd class="mt-2 text-sm font-medium text-zinc-900 dark:text-white">{{ $latestRegistration?->scheme?->name ?: 'Tidak ada' }}</dd>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50/80 px-4 py-4 dark:border-zinc-800 dark:bg-zinc-800/60">
                    <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">Tanggal Daftar</dt>
                    <dd class="mt-2 text-sm font-medium text-zinc-900 dark:text-white">{{ $registrationDate ?: 'Tidak ada' }}</dd>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50/80 px-4 py-4 dark:border-zinc-800 dark:bg-zinc-800/60">
                    <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">Kode Referensi</dt>
                    <dd class="mt-2 text-sm font-medium text-zinc-900 dark:text-white">{{ $latestRegistration?->payment_reference ?: 'Tidak ada' }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-[2rem] border border-zinc-200 bg-slate-50 p-6 shadow-sm dark:border-zinc-800 dark:bg-slate-900/60">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-700 dark:text-blue-300">Sertifikat Aktif</p>
                    <h2 class="mt-4 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ $activeCertificate?->scheme_name ?? 'Tidak ada' }}</h2>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                        {{ $activeCertificate?->level ? $activeCertificate->level . ' - ' : '' }}{{ $certificateExpiry ? 'Berlaku s.d. ' . $certificateExpiry : 'Belum ada sertifikat aktif.' }}
                    </p>
                </div>

                @if ($activeCertificate)
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800/70">
                        Aktif
                    </span>
                @endif
            </div>

            <div class="mt-8 space-y-3">
                @if ($certificateDownloadUrl)
                    <a href="{{ $certificateDownloadUrl }}" target="_blank" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Unduh Sertifikat
                    </a>
                @endif

                @if ($resultDownloadUrl)
                    <a href="{{ $resultDownloadUrl }}" target="_blank" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-zinc-300 px-4 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                        </svg>
                        Unduh Hasil Ujian
                    </a>
                @endif

                @if (! $certificateDownloadUrl && ! $resultDownloadUrl)
                    <div class="rounded-2xl border border-dashed border-zinc-300 px-4 py-6 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-400">
                        Tidak ada
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
