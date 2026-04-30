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
        $shouldShowProfileCompletionNotice = !$profileComplete && !$latestRegistration;
        $isGeneralUser = $dashboardUser->isGeneralUser();
        $canRegisterNewScheme = $latestRegistration?->status === 'sertifikat_terbit';
        $newSchemeAlertMessage =
            'Selesaikan satu skema sertifikasi hingga tahap sertifikat terbit terlebih dahulu sebelum mendaftar skema baru.';
    @endphp

    @include('livewire.user-dashboard._summary-cards')

    {{-- Progress Pendaftaran Section --}}
    <div class="rounded-[1.25rem] bg-white p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] sm:p-6 md:p-8">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-[1.35rem] font-bold text-gray-900">Progress Pendaftaran</h2>
                <p class="mt-1 text-sm text-gray-500">Pantau proses sertifikasi Anda dari pendaftaran hingga sertifikat
                    terbit.</p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
                <div
                    class="inline-flex w-fit justify-center items-center rounded-full px-4 py-2 sm:py-1.5 text-xs font-semibold {{ $statusBadgeClasses }}">
                    <span class="mr-2 h-1.5 w-1.5 rounded-full {{ $statusBadgeDot }}"></span>
                    {{ $registrationStatusLabel }}
                </div>

                @if (!$canRegisterNewScheme)
                    <button type="button" onclick="alert('{{ $newSchemeAlertMessage }}')"
                        class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-400 transition hover:bg-gray-200">
                        <x-svg.plus class="h-4 w-4 shrink-0" />
                        Daftar Skema Baru
                    </button>
                @else
                    <a href="{{ route('dashboard.skema') }}"
                        class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-semibold text-black hover:bg-emerald-500">
                        <x-svg.plus class="h-4 w-4 shrink-0" />
                        Daftar Skema Baru
                    </a>
                @endif
            </div>
        </div>

        @include('livewire.user-dashboard._registration-stepper')

        {{-- Bottom Section: Sertifikat Aktif (LEFT) & Detail Pendaftaran (RIGHT) --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_1fr] xl:grid-cols-[400px_1fr]">

            {{-- LEFT: Sertifikat Aktif Sidebar (Dynamic) --}}
            <div class="rounded-[1.25rem] bg-white p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] sm:p-6 md:p-8">

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
                            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-500">Skema Saat Ini</p>
                            <div class="mt-2 space-y-2">
                                @if ($latestRegistration)
                                    <div
                                        class="flex items-center justify-between rounded-lg bg-white/80 px-3 py-2 text-sm">
                                        <span
                                            class="font-medium text-gray-900">{{ $latestRegistration->scheme?->name ?: '-' }}</span>
                                        <span
                                            class="text-gray-500">{{ $latestRegistration->created_at?->translatedFormat('d M Y') }}</span>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">Belum ada riwayat skema.</p>
                                @endif
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

                            <a href="{{ route('dashboard.daftar-skema', ['type' => 'baru', 'scheme' => $latestRegistration->scheme_id, 'source' => 'dashboard-skema']) }}"
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
                            <a href="{{ Storage::url($activeCertificate->file_path) }}" target="_blank"
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
                                Unduh Surat Keterangan
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
            <div class="rounded-[1.25rem] bg-white p-5 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] sm:p-6 md:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h2 class="text-[1.35rem] font-bold text-gray-900">Detail Pendaftaran</h2>
                    @if ($profileComplete)
                        <a href="{{ route('profile.edit') }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-200">
                            Detail Biodata
                        </a>
                    @endif
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Nama</p>
                        <p class="mt-1.5 font-medium text-gray-900">{{ $dashboardUser->name ?: '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            {{ $isGeneralUser ? 'NIK' : 'NIM' }}</p>
                        <p class="mt-1.5 font-medium text-gray-900">
                            {{ $isGeneralUser ? ($dashboardUser->no_ktp ?: '-') : ($dashboardUser->nim ?: '-') }}
                        </p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Skema</p>
                        <p class="mt-1.5 font-medium text-gray-900">
                            {{ $latestRegistration?->scheme?->name ?: '-' }}
                        </p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            {{ $isGeneralUser ? 'Nama Institusi / Perusahaan' : 'Jurusan' }}</p>
                        <p class="mt-1.5 font-medium text-gray-900">
                            {{ $isGeneralUser ? ($dashboardUser->nama_perusahaan ?: '-') : ($dashboardUser->program_studi ?: '-') }}
                        </p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">No. WhatsApp</p>
                        <p class="mt-1.5 font-medium text-gray-900">{{ $dashboardUser->no_wa ?: '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Tanggal Daftar</p>
                        <p class="mt-1.5 font-medium text-gray-900">{{ $registrationDate }}</p>
                    </div>
                </div>

                @if ($shouldShowProfileCompletionNotice)
                    <div class="mt-6 rounded-3xl border border-dashed border-emerald-200 bg-emerald-50/60 p-6">
                        <div class="flex flex-col gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Biodata Belum
                                    Lengkap</p>
                                <h3 class="mt-2 text-lg font-bold text-gray-900">Lengkapi biodata di tahap kedua daftar
                                    skema</h3>
                                <p class="mt-2 max-w-xl text-sm text-gray-600">
                                    Beberapa data wajib masih perlu dilengkapi agar proses pendaftaran dan dokumen
                                    sertifikasi bisa diproses tanpa kendala.
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
                @endif
            </div>

        </div>
    </div>

    @include('livewire.user-dashboard._auto-scroll-script')
