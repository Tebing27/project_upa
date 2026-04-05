<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    @php
        $currentStep = $latestRegistration ? $this->getStepProgress($latestRegistration->status) : 1;
        $registrationStatusLabel = $latestRegistration ? $this->getStatusLabel($latestRegistration->status) : 'Daftar';

        $steps = [
            1 => 'Daftar',
            2 => 'Verifikasi Data & Dokumen',
            3 => 'Pembayaran',
            4 => 'Jadwal Ujian',
            5 => in_array($latestRegistration?->status, ['selesai_uji', 'kompeten', 'tidak_kompeten'], true)
                ? 'Hasil Ujian'
                : 'Sertifikat Terbit',
        ];

        $statusBadgeClasses = match ($latestRegistration?->status) {
            'dokumen_ditolak', 'rejected', 'tidak_kompeten' => 'bg-red-50 text-red-700 border-red-100',
            'sertifikat_terbit' => 'bg-[#d1fae5] text-emerald-700 border-[#a7f3d0]/50',
            'terjadwal', 'selesai_uji', 'kompeten' => 'bg-blue-50 text-blue-700 border-blue-100',
            'dokumen_ok', 'pending_payment', 'paid' => 'bg-amber-50 text-amber-700 border-amber-100',
            'menunggu_verifikasi' => 'bg-teal-50 text-teal-700 border-teal-100',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };

        $statusBadgeDot = match ($latestRegistration?->status) {
            'dokumen_ditolak', 'rejected', 'tidak_kompeten' => 'bg-red-500',
            'sertifikat_terbit' => 'bg-[#10b981]',
            'terjadwal', 'selesai_uji', 'kompeten' => 'bg-blue-500',
            'dokumen_ok', 'pending_payment', 'paid' => 'bg-amber-500',
            'menunggu_verifikasi' => 'bg-teal-500',
            default => 'bg-slate-400',
        };

        $registrationDate = $latestRegistration?->created_at?->translatedFormat('d F Y') ?? '16 Maret 2026';
        $dashboardUser = auth()->user();
        $profileComplete = $dashboardUser->hasCompletedProfile();
        $isGeneralUser = $dashboardUser->isGeneralUser();
        $canRegisterNewScheme = $latestRegistration?->status === 'sertifikat_terbit';
        $newSchemeAlertMessage =
            'Selesaikan satu skema sertifikasi hingga tahap sertifikat terbit terlebih dahulu sebelum mendaftar skema baru.';
    @endphp

    {{-- Top Cards Section --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Card 1: Sertifikat Aktif --}}
        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Sertifikat Aktif</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $activeCertificatesCount ?? '0' }}</p>
                    <p class="mt-2 text-[13px] text-gray-500">
                        {{ $activeCertificate?->scheme_name ?? 'Tidak ada sertifikat' }}
                    </p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 2: Status Pendaftaran --}}
        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Status Pendaftaran</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $registrationStatusLabel }}</p>
                    <p class="mt-2 text-[13px] text-gray-500">Tahap {{ $currentStep }} dari 5</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 3: Kode Referensi --}}
        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Kode Referensi</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $latestRegistration?->payment_reference ?? '-' }}</p>
                    <p class="mt-2 text-[13px] text-gray-500">Menyesuaikan data pendaftaran terbaru.</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <span class="text-xl font-semibold">#</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Pendaftaran Section --}}
    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-[1.35rem] font-bold text-gray-900">Progress Pendaftaran</h2>
                <p class="mt-1 text-sm text-gray-500">Pantau proses sertifikasi Anda dari pendaftaran hingga sertifikat
                    terbit.</p>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="inline-flex items-center rounded-full px-4 py-1.5 text-xs font-semibold {{ $statusBadgeClasses }}">
                    <span class="mr-2 h-1.5 w-1.5 rounded-full {{ $statusBadgeDot }}"></span>
                    {{ $registrationStatusLabel }}
                </div>

                @if (!$canRegisterNewScheme)
                    <button type="button" onclick="alert('{{ $newSchemeAlertMessage }}')"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-400 transition hover:bg-gray-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Daftar Skema Baru
                    </button>
                @else
                    <a href="{{ route('dashboard.skema') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-semibold text-black hover:bg-emerald-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Daftar Skema Baru
                    </a>
                @endif
            </div>
        </div>

        {{-- Stepper --}}
        <div class="mt-8 mb-4 pb-12">
            <div class="relative flex w-full px-2 md:px-8">
                @php
                    $progressWidth = 0;
                    $progressWidth = match ($currentStep) {
                        2 => 25,
                        3 => 50,
                        4 => 75,
                        5 => 100,
                        default => 0,
                    };
                @endphp

                {{-- Background Line --}}
                <div class="absolute left-0 right-0 top-[19px] h-[2px] bg-slate-200 z-0"></div>

                <div class="absolute left-0 top-[19px] h-[2px] bg-[#10b981] transition-all duration-500 z-0"
                    style="width: {{ $progressWidth }}%;"></div>

                {{-- Stepper Items --}}
                <div class="relative z-10 flex w-full justify-between">
                    @foreach ($steps as $stepNumber => $stepLabel)
                        @php
                            $isCompleted = $stepNumber < $currentStep;
                            $isCurrent = $stepNumber === $currentStep;
                            $isRejectedStep =
                                ($stepNumber === 2 &&
                                    in_array($latestRegistration?->status, ['dokumen_ditolak', 'rejected'], true)) ||
                                ($stepNumber === 5 && $latestRegistration?->status === 'tidak_kompeten');

                            $displayLabel = str_replace(' & ', "\n& ", $stepLabel);
                        @endphp

                        <div class="relative flex shrink-0 flex-col items-center">
                            {{-- Circle --}}
                            <div @class([
                                'relative z-10 flex shrink-0 h-[40px] w-[40px] items-center justify-center rounded-full text-[15px] font-bold ring-[6px] ring-white transition-colors',
                                'bg-[#10b981] text-white' =>
                                    $isCompleted || ($isCurrent && !$isRejectedStep),
                                'bg-red-500 text-white' => $isRejectedStep,
                                'bg-white border-[2px] border-slate-200 text-slate-400' =>
                                    !$isCompleted && !$isCurrent && !$isRejectedStep,
                            ])>
                                @if ($isCompleted)
                                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @elseif ($isRejectedStep)
                                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                @else
                                    {{ $stepNumber }}
                                @endif
                            </div>

                            {{-- Label --}}
                            <div
                                class="absolute top-[52px] left-1/2 w-[120px] -translate-x-1/2 text-center md:w-[150px]">
                                <p @class([
                                    'whitespace-pre-line leading-[1.3] text-[11px] md:text-[13px]',
                                    'text-slate-600 font-medium' => $isCompleted || $isCurrent,
                                    'text-slate-400' => !$isCompleted && !$isCurrent,
                                    'text-red-600 font-medium' => $isRejectedStep,
                                ])>
                                    {{ $displayLabel }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bottom Section: Sertifikat Aktif (LEFT) & Detail Pendaftaran (RIGHT) --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[400px_1fr]">

            {{-- LEFT: Sertifikat Aktif Sidebar (Dynamic) --}}
            <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">

                @if (in_array($latestRegistration?->status, ['dokumen_ditolak', 'rejected'], true))
                    {{-- STATUS: Dokumen Ditolak --}}
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-red-600">Lengkapi Dokumen</p>
                        <span class="rounded-full bg-red-50 px-3 py-1 text-[11px] font-semibold text-red-600">
                            Ditolak
                        </span>
                    </div>

                    <div class="mt-5 space-y-3">
                        @foreach ($rejectedDocuments as $doc)
                            <div class="rounded-xl border border-red-100 bg-red-50/50 p-4">
                                <div class="flex items-center gap-2">
                                    <svg class="h-4 w-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p class="text-sm font-semibold text-red-700">{{ $doc['label'] }} ditolak</p>
                                </div>
                                @if ($doc['note'])
                                    <p class="mt-1.5 pl-6 text-[13px] text-red-600/80">{{ $doc['note'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('dashboard.status', $latestRegistration) }}"
                        class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-3.5 text-sm font-semibold text-white transition-all hover:bg-red-700">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Upload Ulang Dokumen
                    </a>

                    <a href="{{ route('dashboard.status', $latestRegistration) }}"
                        class="mt-3 flex w-full items-center justify-center gap-1 text-sm font-medium text-red-600 transition-colors hover:text-red-700">
                        Lihat status pendaftaran
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @elseif (in_array($latestRegistration?->status, ['menunggu_verifikasi', 'dokumen_kurang'], true))
                    {{-- STATUS: Tahap Review --}}
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Tahap Review</p>
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-semibold text-amber-600">
                            Review
                        </span>
                    </div>

                    <div class="mt-6 flex flex-col items-center text-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-50">
                            <svg class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-gray-900">Sedang Direview</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Dokumen Anda sedang dalam proses verifikasi oleh admin. Mohon tunggu konfirmasi selanjutnya.
                        </p>
                    </div>

                    <a href="{{ route('dashboard.status', $latestRegistration) }}"
                        class="mt-6 flex w-full items-center justify-center gap-1 rounded-xl border border-amber-200 bg-amber-50/50 px-4 py-3.5 text-sm font-semibold text-amber-700 transition-all hover:bg-amber-100">
                        Lihat Detail Status
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @elseif (in_array($latestRegistration?->status, ['dokumen_ok', 'pending_payment', 'paid'], true))
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Tahap Pembayaran</p>
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-[11px] font-semibold text-amber-600">
                            {{ $registrationStatusLabel }}
                        </span>
                    </div>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-xl border border-amber-100 bg-amber-50/40 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-500">Kode Instruksi
                                Pembayaran</p>
                            <p class="mt-1.5 font-mono text-lg font-bold text-gray-900">
                                {{ $latestRegistration->payment_reference }}</p>
                        </div>
                        <div class="rounded-xl border border-amber-100 bg-amber-50/40 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-500">Riwayat Skema</p>
                            <div class="mt-2 space-y-2">
                                @forelse ($allRegistrations->take(3) as $registrationHistory)
                                    <div
                                        class="flex items-center justify-between rounded-lg bg-white/80 px-3 py-2 text-sm">
                                        <span
                                            class="font-medium text-gray-900">{{ $registrationHistory->scheme?->name ?: '-' }}</span>
                                        <span
                                            class="text-gray-500">{{ $registrationHistory->created_at?->translatedFormat('d M Y') }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Belum ada riwayat skema.</p>
                                @endforelse
                            </div>
                        </div>
                        @if (($latestRegistration->document_statuses['payment_proof_path']['note'] ?? null) !== null)
                            <div class="rounded-xl border border-red-100 bg-red-50/50 p-4 text-sm text-red-700">
                                {{ $latestRegistration->document_statuses['payment_proof_path']['note'] }}
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('dashboard.status', $latestRegistration) }}"
                        class="mt-6 flex w-full items-center justify-center gap-1 rounded-xl border border-amber-200 bg-amber-50/50 px-4 py-3.5 text-sm font-semibold text-amber-700 transition-all hover:bg-amber-100">
                        Kelola Pembayaran
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @elseif ($latestRegistration?->status === 'terjadwal')
                    {{-- STATUS: Jadwal Ujian --}}
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Persiapan Ujian</p>
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-[11px] font-semibold text-blue-600">
                            Terjadwal
                        </span>
                    </div>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-400">Link WhatsApp</p>
                            @if ($globalWhatsappLink)
                                <a href="{{ $globalWhatsappLink }}" target="_blank" rel="noopener noreferrer"
                                    class="mt-2 inline-flex items-center gap-2 rounded-xl bg-blue-200 px-4 py-2.5 text-sm font-semibold text-black ">
                                    Buka Grup / Chat
                                </a>
                            @else
                                <p class="mt-1.5 font-semibold text-gray-900">-</p>
                            @endif
                        </div>
                        <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-400">Tanggal Ujian</p>
                            <p class="mt-1.5 font-semibold text-gray-900">
                                {{ $latestRegistration->exam_date?->translatedFormat('d F Y') ?? '-' }}
                            </p>
                        </div>
                        <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-400">Jam Ujian</p>
                            <p class="mt-1.5 font-semibold text-gray-900">
                                Jam {{ $latestRegistration->exam_date?->format('H:i') ?? '-' }}
                            </p>
                        </div>
                        <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-400">Lokasi</p>
                            <p class="mt-1.5 font-semibold text-gray-900">
                                {{ $latestRegistration->exam_location ?? '-' }}
                            </p>
                        </div>
                        <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-400">Asesor</p>
                            <p class="mt-1.5 font-semibold text-gray-900">
                                {{ $latestRegistration->assessor_name ?? '-' }}
                            </p>
                        </div>

                    </div>
                @elseif (in_array($latestRegistration?->status, ['selesai_uji', 'kompeten', 'tidak_kompeten'], true))
                    {{-- STATUS: Hasil Ujian --}}
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Hasil Ujian</p>
                        <span @class([
                            'rounded-full px-3 py-1 text-[11px] font-semibold',
                            'bg-emerald-50 text-emerald-600' =>
                                $latestRegistration->status === 'kompeten',
                            'bg-red-50 text-red-600' =>
                                $latestRegistration->status === 'tidak_kompeten',
                            'bg-indigo-50 text-indigo-600' =>
                                $latestRegistration->status === 'selesai_uji',
                        ])>
                            {{ $this->getStatusLabel($latestRegistration->status) }}
                        </span>
                    </div>

                    <div class="mt-6 flex flex-col items-center text-center">
                        @if ($latestRegistration->status === 'kompeten')
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50">
                                <svg class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-emerald-700">Selamat, Anda Kompeten!</h3>
                            <p class="mt-2 text-sm text-gray-500">Sertifikat Anda akan segera diterbitkan.</p>
                        @elseif ($latestRegistration->status === 'tidak_kompeten')
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-50">
                                <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-red-700">Belum Kompeten</h3>
                            <p class="mt-2 text-sm text-gray-500">Silakan unduh hasil ujian Anda untuk informasi lebih
                                lanjut.</p>

                            @if ($latestRegistration->exam_result_path)
                                <a href="{{ Storage::url($latestRegistration->exam_result_path) }}" target="_blank"
                                    class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-100">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                                    </svg>
                                    Unduh Hasil Ujian
                                </a>
                            @endif

                            <a href="{{ route('dashboard.daftar-skema', ['type' => 'perpanjangan', 'scheme' => $latestRegistration->scheme_id, 'source' => 'dashboard-skema']) }}"
                                class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-3.5 text-sm font-semibold text-white transition-all hover:bg-red-700">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Daftar Ulang Skema Ini
                            </a>
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-50">
                                <svg class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-gray-900">Ujian Selesai</h3>
                            <p class="mt-2 text-sm text-gray-500">Hasil ujian sedang diproses oleh asesor.</p>
                        @endif
                    </div>
                @elseif ($latestRegistration?->status === 'sertifikat_terbit' && $activeCertificate)
                    {{-- STATUS: Sertifikat Terbit --}}
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-[#1b8a6b]">Sertifikat Aktif</p>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-[#1b8a6b]">
                            Aktif
                        </span>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $activeCertificate->scheme_name }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Berlaku hingga
                            {{ $activeCertificate->expired_date?->translatedFormat('d F Y') ?? '-' }}
                        </p>
                    </div>

                    <div class="mt-8 flex flex-col gap-3">
                        @if ($activeCertificate->file_path)
                            <a href="{{ route('dashboard.certificates') }}"
                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#1b8a6b] px-4 py-3.5 text-sm font-semibold text-white transition-all hover:bg-[#157158]">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh Sertifikat
                            </a>
                        @endif

                        @if ($activeCertificate?->result_file_path || $latestRegistration?->exam_result_path)
                            <a href="{{ Storage::url($activeCertificate?->result_file_path ?? $latestRegistration->exam_result_path) }}"
                                target="_blank"
                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-100">
                                <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" />
                                </svg>
                                Unduh Hasil Ujian
                            </a>
                        @endif
                    </div>
                @else
                    {{-- DEFAULT: Tidak Ada Pendaftaran Aktif --}}
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Status Pendaftaran</p>
                    </div>

                    <div class="mt-6 flex flex-col items-center text-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                            <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-gray-900">Mulai pendaftaran Anda</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Anda belum memiliki pendaftaran sertifikasi yang aktif saat ini. Pilih skema untuk memulai
                            tahap daftar.
                        </p>
                    </div>
                @endif
            </div>

            {{-- RIGHT: Detail Pendaftaran --}}
            <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-[1.35rem] font-bold text-gray-900">Detail Pendaftaran</h2>
                    @if ($profileComplete)
                        <a href="{{ route('profile.edit') }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-200">
                            Detail Biodata
                        </a>
                    @endif
                </div>

                @if (!$profileComplete)
                    <div class="mt-6 rounded-3xl border border-dashed border-emerald-200 bg-emerald-50/60 p-6">
                        <div class="flex flex-col gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Biodata Belum
                                    Lengkap</p>
                                <h3 class="mt-2 text-lg font-bold text-gray-900">Lengkapi biodata di tahap kedua daftar
                                    skema</h3>
                                <p class="mt-2 max-w-xl text-sm text-gray-600">
                                    Ringkasan detail pendaftaran masih kosong karena biodata peserta umum belum
                                    dilengkapi.
                                    Sekarang biodata dilengkapi langsung di halaman daftar skema pada tahap kedua,
                                    setelah pemilihan skema.
                                </p>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('dashboard.skema') }}" wire:navigate
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-white px-5 py-3 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50">
                                    Lihat Skema
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                                <a href="{{ route('dashboard.daftar-skema') }}" wire:navigate
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-black transition hover:bg-emerald-400">
                                    Daftar Skema
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Nama</p>
                            <p class="mt-1.5 font-medium text-gray-900">{{ $dashboardUser->name }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                {{ $isGeneralUser ? 'NIK' : 'NIM' }}</p>
                            <p class="mt-1.5 font-medium text-gray-900">
                                {{ $isGeneralUser ? ($dashboardUser->no_ktp ?: '-') : ($dashboardUser->nim ?: '-') }}
                            </p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                {{ $isGeneralUser ? 'Instansi Pendidikan' : 'Program Studi' }}</p>
                            <p class="mt-1.5 font-medium text-gray-900">
                                {{ $isGeneralUser ? ($dashboardUser->nama_institusi ?: '-') : ($dashboardUser->program_studi ?: '-') }}
                            </p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                {{ $isGeneralUser ? 'Jurusan / Program Studi' : 'Skema' }}</p>
                            <p class="mt-1.5 font-medium text-gray-900">
                                {{ $isGeneralUser ? ($dashboardUser->program_studi ?: '-') : ($latestRegistration?->scheme?->name ?: '-') }}
                            </p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                {{ $isGeneralUser ? 'Pekerjaan' : 'Tanggal Daftar' }}</p>
                            <p class="mt-1.5 font-medium text-gray-900">
                                {{ $isGeneralUser ? ($dashboardUser->pekerjaan ?: '-') : $registrationDate }}</p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                {{ $isGeneralUser ? 'No. WhatsApp' : 'Kode Referensi' }}</p>
                            <p class="mt-1.5 font-medium text-gray-900">
                                {{ $isGeneralUser ? ($dashboardUser->no_wa ?: '-') : ($latestRegistration?->payment_reference ?: '-') }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
