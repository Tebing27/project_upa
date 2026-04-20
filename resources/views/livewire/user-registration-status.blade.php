<div class="min-h-screen bg-[#f8fafc] p-6 md:p-8 font-sans">
    @php
        $steps = [
            1 => 'Daftar',
            2 => 'Verifikasi Data & Dokumen',
            3 => 'Pembayaran',
            4 => 'Jadwal Ujian',
            5 => in_array($registration?->status, ['selesai_uji', 'kompeten', 'tidak_kompeten'], true)
                ? 'Hasil Ujian'
                : 'Sertifikat Terbit',
        ];

        $statusBadgeClasses = match ($registration?->status) {
            'dokumen_ditolak', 'rejected', 'tidak_kompeten' => 'bg-red-50 text-red-700 border-red-100',
            'sertifikat_terbit' => 'bg-[#d1fae5] text-emerald-700 border-[#a7f3d0]/50',
            'terjadwal', 'selesai_uji', 'kompeten' => 'bg-blue-50 text-blue-700 border-blue-100',
            'dokumen_ok', 'pending_payment', 'paid' => 'bg-amber-50 text-amber-700 border-amber-100',
            'menunggu_verifikasi' => 'bg-teal-50 text-teal-700 border-teal-100',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };

        $statusBadgeDot = match ($registration?->status) {
            'dokumen_ditolak', 'rejected', 'tidak_kompeten' => 'bg-red-500',
            'sertifikat_terbit' => 'bg-[#10b981]',
            'terjadwal', 'selesai_uji', 'kompeten' => 'bg-blue-500',
            'dokumen_ok', 'pending_payment', 'paid' => 'bg-amber-500',
            'menunggu_verifikasi' => 'bg-teal-500',
            default => 'bg-slate-400',
        };

        $statusStyles = [
            'verified' => 'border-[#a7f3d0]/60 bg-[#f0fdf4]',
            'pending' => 'border-amber-100 bg-[#fffbeb]',
            'rejected' => 'border-red-100 bg-[#fef2f2]',
            'missing' => 'border-slate-200 bg-white',
        ];

        $statusTextColors = [
            'verified' => 'text-[#059669]',
            'pending' => 'text-amber-600',
            'rejected' => 'text-red-600',
            'missing' => 'text-slate-500',
        ];

        $statusIconBgs = [
            'verified' => 'bg-[#d1fae5] text-[#059669]',
            'pending' => 'bg-amber-100/70 text-amber-600',
            'rejected' => 'bg-red-100/70 text-red-600',
            'missing' => 'bg-slate-100 text-slate-500',
        ];

        $statusLabels = [
            'verified' => 'Terverifikasi',
            'pending' => 'Menunggu Review',
            'rejected' => 'Ditolak',
            'missing' => 'Belum Upload',
        ];

        $historyColors = [
            'blue' => 'bg-[#3b82f6]',
            'amber' => 'bg-[#f59e0b]',
            'red' => 'bg-[#ef4444]',
            'indigo' => 'bg-[#6366f1]',
            'emerald' => 'bg-[#10b981]',
            'purple' => 'bg-[#8b5cf6]',
        ];

        $historyBgColors = [
            'blue' => 'bg-blue-100/80',
            'amber' => 'bg-amber-100/80',
            'red' => 'bg-red-100/80',
            'indigo' => 'bg-indigo-100/80',
            'emerald' => 'bg-emerald-100/80',
            'purple' => 'bg-purple-100/80',
        ];
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">
        @if (!$registration)
            <div
                class="rounded-[1.25rem] border border-slate-100 bg-white p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
                <div class="flex flex-col items-center text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h1 class="mt-4 text-2xl font-bold text-slate-800">Belum Ada Pendaftaran</h1>
                    <p class="mt-2 max-w-xl text-sm text-slate-500">
                        Halaman status pendaftaran tetap bisa dibuka, tetapi saat ini Anda belum memiliki data
                        pendaftaran yang aktif ataupun riwayat pendaftaran terbaru untuk ditampilkan.
                    </p>
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('dashboard.skema') }}"
                            class="inline-flex items-center rounded-xl bg-emerald-400 px-5 py-3 text-sm font-semibold text-black transition hover:bg-emerald-500">
                            Lihat Skema
                        </a>
                        <a href="{{ route('dashboard.daftar-skema') }}"
                            class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Daftar Skema
                        </a>
                    </div>
                </div>
            </div>

            @php return; @endphp
        @endif

        <!-- Header -->
        <div>
            <h1 class="text-2xl md:text-[28px] font-bold tracking-tight text-[#1e293b]">Status Pendaftaran</h1>
            <p class="mt-1.5 text-[15px] text-slate-500">Pantau progres pendaftaran, cek status dokumen, dan upload
                ulang
                dokumen yang ditolak.</p>
        </div>

        @if ($successMessage)
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ $successMessage }}
            </div>
        @endif

        <!-- User Info & Stepper Card -->
        <section
            class="rounded-[1.25rem] border border-slate-100 bg-white p-6 md:p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h2>
                    <p class="mt-1 text-[15px] text-slate-500">
                        {{ $registration?->scheme?->name ?: 'Skema belum dipilih' }} <span class="mx-1.5">&bull;</span>
                        {{ auth()->user()->isGeneralUser() ? 'NIK' : 'NIM' }}:
                        {{ auth()->user()->isGeneralUser() ? (auth()->user()->no_ktp ?: '-') : (auth()->user()->nim ?: '-') }}
                    </p>
                </div>

                <div
                    class="inline-flex items-center rounded-full px-4 py-1.5 text-[13px] font-semibold {{ $statusBadgeClasses }}">
                    <span class="mr-2 h-1.5 w-1.5 rounded-full {{ $statusBadgeDot }}"></span>
                    {{ $statusLabel }}
                </div>
            </div>

            <!-- Stepper -->
            <div class="mt-8 mb-4 pb-12">
                <div class="relative flex w-full px-8"> @php
                    $progressWidth = 0;
                    $progressWidth = match ($currentStep) {
                        2 => 25,
                        3 => 50,
                        4 => 75,
                        5 => 100,
                        default => 0,
                    };
                @endphp

                    <div class="absolute left-0 right-0 top-[19px] h-[2px] bg-slate-200 z-0"></div>

                    <div class="absolute left-0 top-[19px] h-[2px] bg-[#10b981] transition-all duration-500 z-0"
                        style="width: {{ $progressWidth }}%;"></div>

                    <div class="relative flex w-full justify-between z-10">
                        @foreach ($steps as $stepNumber => $stepLabel)
                            @php
                                $isCompleted = $stepNumber < $currentStep;
                                $isCurrent = $stepNumber === $currentStep;
                                $isRejectedStep =
                                    ($stepNumber === 2 &&
                                        in_array($registration?->status, ['dokumen_ditolak', 'rejected'], true)) ||
                                    ($stepNumber === 5 && $registration?->status === 'tidak_kompeten');

                                $displayLabel = str_replace(' & ', "\n& ", $stepLabel);
                            @endphp

                            <div class="relative flex flex-col items-center shrink-0">
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

                                <div
                                    class="absolute top-[52px] left-1/2 -translate-x-1/2 w-[120px] md:w-[150px] text-center">
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
        </section>

        @php
            $biodataItems = $registration->user->isGeneralUser()
                ? [
                    'Nama Lengkap' => $registration->user->name ?: '-',
                    'Email' => $registration->user->email ?: '-',
                    'NIK' => $registration->user->no_ktp ?: '-',
                    'Tempat Lahir' => $registration->user->tempat_lahir ?: '-',
                    'Tanggal Lahir' => $registration->user->tanggal_lahir
                        ? \Carbon\Carbon::parse($registration->user->tanggal_lahir)->translatedFormat('d M Y')
                        : '-',
                    'Jenis Kelamin' => $registration->user->jenis_kelamin === 'L'
                        ? 'Laki-laki'
                        : ($registration->user->jenis_kelamin === 'P' ? 'Perempuan' : '-'),
                    'Nomor WhatsApp' => $registration->user->no_wa ?: '-',
                    'Alamat Rumah' => $registration->user->alamat_rumah ?: '-',
                    'Telepon Rumah' => $registration->user->profile?->telp_rumah ?: '-',
                    'Telepon Kantor' => $registration->user->profile?->telp_kantor ?: '-',
                    'Tujuan Asesmen' => $registration->assessmentPurposeLabel(),
                    'Kualifikasi Pendidikan' => $registration->user->kualifikasi_pendidikan ?: '-',
                    'Nama Institusi / Perusahaan' => $registration->user->nama_perusahaan ?: '-',
                    'Jabatan' => $registration->user->jabatan ?: '-',
                    'Alamat Perusahaan' => $registration->user->alamat_perusahaan ?: '-',
                    'Kode Pos Perusahaan' => $registration->user->kode_pos_perusahaan ?: '-',
                    'Telepon Perusahaan' => $registration->user->no_telp_perusahaan ?: '-',
                    'Email Perusahaan' => $registration->user->email_perusahaan ?: '-',
                ]
                : [
                    'Nama Lengkap' => $registration->user->name ?: '-',
                    'Email' => $registration->user->email ?: '-',
                    'NIM' => $registration->user->nim ?: '-',
                    'Tempat Lahir' => $registration->user->tempat_lahir ?: '-',
                    'Tanggal Lahir' => $registration->user->tanggal_lahir
                        ? \Carbon\Carbon::parse($registration->user->tanggal_lahir)->translatedFormat('d M Y')
                        : '-',
                    'Jenis Kelamin' => $registration->user->jenis_kelamin === 'L'
                        ? 'Laki-laki'
                        : ($registration->user->jenis_kelamin === 'P' ? 'Perempuan' : '-'),
                    'Nomor WhatsApp' => $registration->user->no_wa ?: '-',
                    'Alamat Rumah' => $registration->user->alamat_rumah ?: '-',
                    'Tujuan Asesmen' => $registration->assessmentPurposeLabel(),
                    'Fakultas' => $registration->user->fakultas ?: '-',
                    'Program Studi' => $registration->user->program_studi ?: '-',
                    'Total SKS' => $registration->user->total_sks !== null ? (string) $registration->user->total_sks : '-',
                    'Status Semester' => $registration->user->status_semester ?: '-',
                ];
        @endphp

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.8fr_1fr]">
            <!-- Detail Section -->
            <section
                class="rounded-[1.25rem] border border-slate-100 bg-white p-6 md:p-7 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
                <div
                    class="flex flex-col gap-4 border-b border-slate-100 pb-5 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Detail Peserta</p>
                        <h2 class="mt-2 text-[1.15rem] font-bold text-slate-800">
                            {{ match ($activeTab) {
                                'biodata' => 'Biodata Peserta',
                                'dokumen' => 'Dokumen Pendaftaran',
                                'tanda_tangan' => 'Tanda Tangan',
                                'pembayaran' => 'Pembayaran',
                                'jadwal' => 'Jadwal Ujian',
                                default => 'Detail Peserta',
                            } }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            @if ($activeTab === 'biodata')
                                Biodata tetap bisa dicek kapan saja. Perubahan hanya dibuka ketika ada dokumen yang ditolak admin.
                            @elseif ($activeTab === 'dokumen')
                                Pantau hasil review dokumen, lihat catatan admin, dan upload ulang jika diperlukan.
                            @elseif ($activeTab === 'tanda_tangan')
                                Pantau tanda tangan pemohon dan finalisasi verifikasi admin pada tahap ini.
                            @elseif ($activeTab === 'pembayaran')
                                Kelola instruksi pembayaran dan upload bukti pembayaran pada tahap ini.
                            @else
                                Detail ujian ditampilkan lebih ringkas agar peserta langsung melihat jadwal, lokasi, dan akses koordinasi.
                            @endif
                        </p>
                    </div>

                    @if ($canEditBiodata && !$isEditingBiodata && $activeTab === 'biodata')
                        <button type="button" wire:click="startEditingBiodata"
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Update Biodata
                        </button>
                    @endif
                </div>

                <div class="mt-5 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="inline-flex w-full flex-wrap rounded-2xl bg-slate-100 p-1 md:w-auto">
                        <button type="button" wire:click="setActiveTab('biodata')" @class([
                            'flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold transition md:flex-none',
                            'bg-white text-slate-800 shadow-sm' => $activeTab === 'biodata',
                            'text-slate-500 hover:text-slate-700' => $activeTab !== 'biodata',
                        ])>
                            Biodata
                            @if ($canEditBiodata)
                                <span class="ml-2 rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-bold text-amber-700">
                                    Bisa Edit
                                </span>
                            @endif
                        </button>
                        <button type="button" wire:click="setActiveTab('dokumen')" @class([
                            'flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold transition md:flex-none',
                            'bg-white text-slate-800 shadow-sm' => $activeTab === 'dokumen',
                            'text-slate-500 hover:text-slate-700' => $activeTab !== 'dokumen',
                        ])>
                            Dokumen
                        </button>
                        <button type="button" wire:click="setActiveTab('tanda_tangan')" @class([
                            'flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold transition md:flex-none',
                            'bg-white text-slate-800 shadow-sm' => $activeTab === 'tanda_tangan',
                            'text-slate-500 hover:text-slate-700' => $activeTab !== 'tanda_tangan',
                        ])>
                            Tanda Tangan
                        </button>
                        <button type="button" wire:click="setActiveTab('pembayaran')" @class([
                            'flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold transition md:flex-none',
                            'bg-white text-slate-800 shadow-sm' => $activeTab === 'pembayaran',
                            'text-slate-500 hover:text-slate-700' => $activeTab !== 'pembayaran',
                        ])>
                            Pembayaran
                        </button>
                        @if (in_array($registration->status, ['terjadwal', 'sertifikat_terbit', 'tidak_kompeten'], true) && $registration->exam_date)
                            <button type="button" wire:click="setActiveTab('jadwal')" @class([
                                'flex-1 rounded-xl px-4 py-2.5 text-sm font-semibold transition md:flex-none',
                                'bg-white text-slate-800 shadow-sm' => $activeTab === 'jadwal',
                                'text-slate-500 hover:text-slate-700' => $activeTab !== 'jadwal',
                            ])>
                                Jadwal Ujian
                            </button>
                        @endif
                    </div>

                    @if ($activeTab === 'dokumen')
                        <span class="text-[13px] font-medium text-slate-400">
                            {{ count($documentCards) }} bukti kelengkapan pemohon
                        </span>
                    @elseif ($activeTab === 'tanda_tangan')
                        <span class="text-[13px] font-medium text-slate-400">
                            {{ $this->shouldDisplayAdminSignature() ? 'Tanda tangan admin tersedia' : 'Menunggu finalisasi admin' }}
                        </span>
                    @elseif ($activeTab === 'jadwal' && $registration->exam_date)
                        <span class="text-[13px] font-medium text-slate-400">
                            Terbit {{ $registration->exam_date->translatedFormat('d M Y H:i') }} WIB
                        </span>
                    @elseif(in_array(
                            $registration->status,
                            ['dokumen_ok', 'pending_payment', 'paid', 'terjadwal', 'kompeten', 'tidak_kompeten', 'sertifikat_terbit'],
                            true))
                        <span class="text-[13px] font-medium text-slate-400">
                            {{ $registration->payment_proof_path ? 'Bukti sudah diupload' : 'Menunggu upload bukti' }}
                        </span>
                    @elseif($canEditBiodata)
                        <span class="text-[13px] font-medium text-slate-400">
                            Ada revisi biodata yang bisa Anda lakukan sebelum upload ulang.
                        </span>
                    @else
                        <span class="text-[13px] font-medium text-slate-400">
                            Pastikan biodata tetap sesuai dengan dokumen yang diupload.
                        </span>
                    @endif
                </div>

                @if ($activeTab === 'biodata')
                    @if ($canEditBiodata)
                        <div class="mt-6 rounded-[1.15rem] border border-amber-200 bg-gradient-to-r from-amber-50 to-white p-4">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-amber-900">Ada dokumen yang ditolak admin</p>
                                    <p class="mt-1 text-sm leading-relaxed text-amber-800">
                                        Supaya perbaikan terasa jelas, kami membuka edit biodata di tab ini. Perbarui data yang
                                        terkait, lalu lanjutkan upload ulang dokumen yang ditolak di tab dokumen.
                                    </p>
                                </div>

                                @if (!$isEditingBiodata)
                                    <button type="button" wire:click="startEditingBiodata"
                                        class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-white px-4 py-2.5 text-sm font-semibold text-amber-900 transition hover:bg-amber-50">
                                        Edit Sekarang
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($isEditingBiodata)
                        <form wire:submit="saveBiodata" class="mt-6 space-y-6">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div><label class="block text-sm font-semibold text-slate-700">Nama Lengkap</label><input
                                        type="text" wire:model="profile.nama"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.nama')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Email</label><input
                                        type="email" wire:model="profile.email"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @if (! $this->isGeneralUser())
                                    <div><label class="block text-sm font-semibold text-slate-700">NIM</label><input type="text"
                                        wire:model="profile.nim"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.nim')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                                @if ($this->isGeneralUser())
                                    <div><label class="block text-sm font-semibold text-slate-700">NIK</label><input type="text"
                                        wire:model="profile.no_ktp"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.no_ktp')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                                <div><label class="block text-sm font-semibold text-slate-700">Tempat Lahir</label><input
                                        type="text" wire:model="profile.tempat_lahir"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.tempat_lahir')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Tanggal Lahir</label><input
                                        type="date" wire:model="profile.tanggal_lahir"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.tanggal_lahir')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Jenis Kelamin</label><select
                                        wire:model="profile.jenis_kelamin"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('profile.jenis_kelamin')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Nomor WhatsApp</label><input
                                        type="text" wire:model="profile.no_wa"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.no_wa')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2"><label class="block text-sm font-semibold text-slate-700">Alamat
                                        Rumah</label>
                                    <textarea wire:model="profile.alamat_rumah" rows="3"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"></textarea>
                                    @error('profile.alamat_rumah')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Kualifikasi
                                        Pendidikan</label><input type="text" wire:model="profile.kualifikasi_pendidikan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.kualifikasi_pendidikan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @if (! $this->isGeneralUser())
                                    <div><label class="block text-sm font-semibold text-slate-700">Fakultas</label><input
                                        type="text" wire:model="profile.fakultas"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.fakultas')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label class="block text-sm font-semibold text-slate-700">Program Studi</label><input
                                        type="text" wire:model="profile.program_studi"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.program_studi')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label class="block text-sm font-semibold text-slate-700">Total SKS</label><input
                                        type="number" wire:model="profile.total_sks"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.total_sks')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label class="block text-sm font-semibold text-slate-700">Status Semester</label><input
                                        type="text" wire:model="profile.status_semester"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.status_semester')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                                @if ($this->isGeneralUser())
                                    <div><label class="block text-sm font-semibold text-slate-700">Telepon Rumah</label><input
                                        type="text" wire:model="profile.telp_rumah"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.telp_rumah')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label class="block text-sm font-semibold text-slate-700">Telepon Kantor</label><input
                                        type="text" wire:model="profile.telp_kantor"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.telp_kantor')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label class="block text-sm font-semibold text-slate-700">Nama Institusi / Perusahaan</label><input
                                        type="text" wire:model="profile.nama_perusahaan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.nama_perusahaan')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label class="block text-sm font-semibold text-slate-700">Jabatan</label><input
                                        type="text" wire:model="profile.jabatan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.jabatan')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label class="block text-sm font-semibold text-slate-700">Kode Pos
                                        Perusahaan</label><input type="text" wire:model="profile.kode_pos_perusahaan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.kode_pos_perusahaan')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label class="block text-sm font-semibold text-slate-700">Telepon Perusahaan</label><input
                                        type="text" wire:model="profile.no_telp_perusahaan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.no_telp_perusahaan')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label class="block text-sm font-semibold text-slate-700">Email Perusahaan</label><input
                                        type="email" wire:model="profile.email_perusahaan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        @error('profile.email_perusahaan')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                                @if ($this->isGeneralUser())
                                    <div class="md:col-span-2"><label class="block text-sm font-semibold text-slate-700">Alamat
                                        Perusahaan</label>
                                        <textarea wire:model="profile.alamat_perusahaan" rows="3"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"></textarea>
                                        @error('profile.alamat_perusahaan')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-5">
                                <button type="button" wire:click="cancelEditingBiodata"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-emerald-400">
                                    Simpan Biodata
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($biodataItems as $label => $value)
                                <article class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                        {{ $label }}</p>
                                    <p class="mt-2 text-sm font-semibold leading-relaxed text-slate-800">{{ $value }}
                                    </p>
                                </article>
                            @endforeach
                        </div>
                    @endif
                @elseif ($activeTab === 'jadwal')
                    <div
                        class="mt-6 rounded-[1.25rem] border border-blue-100 bg-gradient-to-r from-blue-50 via-white to-emerald-50 p-5 md:p-6">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Jadwal Ujian</p>
                                <h3 class="mt-2 text-[1.15rem] font-bold text-slate-800">
                                    {{ $registration->scheme?->name ?: 'Skema Sertifikasi' }}
                                </h3>
                                <p class="mt-2 max-w-2xl text-sm text-slate-600">
                                    Jadwal Anda sudah diterbitkan admin. Simpan detail berikut dan gunakan link WhatsApp
                                    untuk koordinasi lebih lanjut.
                                </p>
                            </div>

                            @if ($globalWhatsappLink)
                                <a href="{{ $globalWhatsappLink }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex items-center justify-center rounded-xl bg-emerald-400 px-5 py-3 text-sm font-semibold text-black transition hover:bg-emerald-500">
                                    Buka Link WhatsApp
                                </a>
                            @endif
                        </div>

                        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <article class="rounded-2xl border border-white/70 bg-white/80 p-4">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Tanggal & Waktu</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">
                                    {{ $registration->exam_date?->translatedFormat('d M Y, H:i') ?? '-' }} WIB
                                </p>
                            </article>
                            <article class="rounded-2xl border border-white/70 bg-white/80 p-4">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Lokasi</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ $registration->exam_location ?: '-' }}</p>
                            </article>
                            <article class="rounded-2xl border border-white/70 bg-white/80 p-4">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Asesor</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ $registration->assessor_name ?: '-' }}</p>
                            </article>
                            <article class="rounded-2xl border border-white/70 bg-white/80 p-4">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Link WhatsApp</p>
                                @if ($globalWhatsappLink)
                                    <a href="{{ $globalWhatsappLink }}" target="_blank" rel="noopener noreferrer"
                                        class="mt-2 inline-flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                        Buka Sekarang
                                    </a>
                                @else
                                    <p class="mt-2 text-sm font-semibold text-slate-800">-</p>
                                @endif
                            </article>
                        </div>
                    </div>
                @elseif ($activeTab === 'dokumen')
                    <div class="mt-6">
                        @if ($canEditBiodata)
                            <div class="mb-4 rounded-[1.15rem] border border-blue-200 bg-gradient-to-r from-blue-50 to-white p-4">
                                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-blue-900">Biodata masih bisa diperbarui</p>
                                        <p class="mt-1 text-sm leading-relaxed text-blue-800">
                                            Jika revisi dokumen berkaitan dengan data peserta, buka tab biodata untuk memperbarui informasi sebelum upload ulang dokumen.
                                        </p>
                                    </div>
                                    <button type="button" wire:click="setActiveTab('biodata')"
                                        class="inline-flex items-center justify-center rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-blue-900 transition hover:bg-blue-50">
                                        Buka Biodata
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if ($documentCards === [])
                            <p class="text-sm text-slate-500">Belum ada data dokumen.</p>
                        @else
                            <div class="overflow-hidden rounded-[1.15rem] border border-slate-200">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="w-16 px-4 py-3 text-left font-semibold text-slate-600">No.</th>
                                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Bukti Persyaratan Dasar</th>
                                            <th class="w-[22rem] px-4 py-3 text-left font-semibold text-slate-600">Upload / Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 bg-white">
                                        @foreach ($documentCards as $document)
                                            <tr class="{{ $statusStyles[$document['status']] ?? $statusStyles['missing'] }}">
                                                <td class="px-4 py-4 align-top font-semibold text-slate-500">{{ $loop->iteration }}.</td>
                                                <td class="px-4 py-4 align-top">
                                                    <p class="text-[14px] font-semibold text-slate-800">{{ $document['label'] }}</p>
                                                </td>
                                                <td class="px-4 py-4 align-top">
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-3">
                                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $statusIconBgs[$document['status']] ?? $statusIconBgs['missing'] }}">
                                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h7.586A2 2 0 0116 4.586L19.414 8A2 2 0 0120 9.414V18a2 2 0 01-2 2z" />
                                                                </svg>
                                                            </div>
                                                            <div class="flex items-center gap-1.5 {{ $statusTextColors[$document['status']] ?? $statusTextColors['missing'] }}">
                                                                @if ($document['status'] === 'verified')
                                                                    <svg class="h-[13px] w-[13px]" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                                                    </svg>
                                                                @elseif($document['status'] === 'rejected')
                                                                    <svg class="h-[13px] w-[13px]" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                                                                    </svg>
                                                                @else
                                                                    <svg class="h-[13px] w-[13px]" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                                                    </svg>
                                                                @endif
                                                                <span class="text-[12px] font-medium">{{ $statusLabels[$document['status']] ?? 'Belum diketahui' }}</span>
                                                            </div>
                                                        </div>

                                                        @if ($document['note'])
                                                            <p class="text-[13px] text-slate-600">{{ $document['note'] }}</p>
                                                        @endif

                                                        @if ($document['has_file'] && $document['file_url'])
                                                            <a href="{{ $document['file_url'] }}" target="_blank" class="inline-flex w-fit items-center rounded-full border border-[#a7f3d0]/60 bg-white px-3 py-1 text-[12px] font-medium text-[#059669] transition hover:bg-emerald-50">
                                                                Lihat File
                                                            </a>
                                                        @else
                                                            <span class="inline-flex w-fit items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-[12px] font-medium text-slate-400">
                                                                Belum Ada File
                                                            </span>
                                                        @endif

                                                        @if ($document['can_upload_optional'])
                                                            <form wire:submit="reuploadDocument('{{ $document['field'] }}')" class="border-t border-slate-200/60 pt-3">
                                                                <label class="block text-xs font-semibold tracking-wider text-slate-500">
                                                                    Jika sudah memiliki, Anda bisa upload!
                                                                </label>
                                                                <div class="mt-3 flex gap-2">
                                                                    <input type="file" wire:model="reuploadFiles.{{ $document['field'] }}" class="block w-full text-xs text-slate-500 file:mr-2 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200" />
                                                                    <button type="submit" class="shrink-0 rounded-md bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700">
                                                                        Upload
                                                                    </button>
                                                                </div>
                                                                @error('reuploadFiles.' . $document['field'])
                                                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                                                @enderror
                                                            </form>
                                                        @endif

                                                        @if ($document['can_reupload'])
                                                            <form wire:submit="reuploadDocument('{{ $document['field'] }}')" class="border-t border-slate-200/60 pt-3">
                                                                <div class="flex gap-2">
                                                                    <input type="file" wire:model="reuploadFiles.{{ $document['field'] }}" class="block w-full text-xs text-slate-500 file:mr-2 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200" />
                                                                    <button type="submit" class="shrink-0 rounded-md bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700">
                                                                        Upload
                                                                    </button>
                                                                </div>
                                                                @error('reuploadFiles.' . $document['field'])
                                                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                                                @enderror
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @elseif ($activeTab === 'tanda_tangan')
                    <div class="mt-6 space-y-5">
                        @if ($this->shouldDisplayAdminSignature())
                            <div class="rounded-[1.25rem] border border-emerald-100 bg-gradient-to-r from-emerald-50 via-white to-white p-5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Tanda Tangan Verifikator</p>
                                <h3 class="mt-2 text-lg font-bold text-slate-800">{{ $registration->admin_signatory_name ?: '-' }}</h3>
                                <p class="mt-1 text-sm text-slate-500">Semua dokumen telah diverifikasi admin.</p>

                                @if ($registration->admin_signature_path)
                                    <div class="mt-5 overflow-hidden rounded-2xl border border-emerald-100 bg-white p-4">
                                        <img src="{{ Storage::url($registration->admin_signature_path) }}" alt="Tanda tangan admin"
                                            class="h-40 w-full object-contain">
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="rounded-[1.25rem] border border-amber-100 bg-gradient-to-r from-amber-50 via-white to-white p-5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Tanda Tangan Pemohon</p>
                                <h3 class="mt-2 text-lg font-bold text-slate-800">{{ $registration->user->name ?: '-' }}</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Tanda tangan admin akan tampil di tab ini setelah seluruh dokumen selesai diverifikasi.
                                </p>

                                @if ($registration->applicant_signature_path)
                                    <div class="mt-5 overflow-hidden rounded-2xl border border-amber-100 bg-white p-4">
                                        <img src="{{ Storage::url($registration->applicant_signature_path) }}" alt="Tanda tangan pemohon"
                                            class="h-40 w-full object-contain">
                                    </div>
                                @else
                                    <div class="mt-5 rounded-2xl border border-dashed border-slate-200 bg-white p-5 text-sm text-slate-500">
                                        Tanda tangan pemohon belum tersedia.
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($registration->applicant_signature_path && $this->shouldDisplayAdminSignature())
                            <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Arsip Tanda Tangan Pemohon</p>
                                <div class="mt-4 overflow-hidden rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <img src="{{ Storage::url($registration->applicant_signature_path) }}" alt="Arsip tanda tangan pemohon"
                                        class="h-32 w-full object-contain">
                                </div>
                            </div>

                            <div class="rounded-[1.25rem] border border-slate-200 bg-slate-50 p-5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Unduh Dokumen</p>
                                <h3 class="mt-2 text-lg font-bold text-slate-800">FR.APL.01 Final</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    File PDF ini tersedia setelah seluruh dokumen diverifikasi dan tanda tangan admin LSP ditempelkan ke pendaftaran Anda.
                                </p>

                                <div class="mt-4">
                                    <a href="{{ route('dashboard.status.apl01.download', $registration) }}"
                                        class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                                        Download PDF FR.APL.01
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif ($activeTab === 'pembayaran')
                    <div class="mt-6">
                        @if (in_array(
                                $registration->status,
                                ['dokumen_ok', 'pending_payment', 'paid', 'terjadwal', 'kompeten', 'tidak_kompeten', 'sertifikat_terbit'],
                                true))
                            <div class="rounded-2xl border border-amber-100 bg-amber-50/50 p-5">
                                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <h3 class="text-base font-bold text-amber-900">Pembayaran</h3>
                                        <p class="mt-1 text-sm text-amber-800">Gunakan kode instruksi pembayaran
                                            berikut lalu upload bukti pembayarannya.</p>
                                    </div>
                                    <div
                                        class="rounded-xl bg-white px-4 py-3 text-sm font-bold text-gray-900 shadow-sm">
                                        {{ $registration->payment_reference }}
                                    </div>
                                </div>

                                @if ($registration->payment_proof_path)
                                    <div class="mt-4 flex flex-wrap items-center gap-3">
                                        <a href="{{ Storage::url($registration->payment_proof_path) }}"
                                            target="_blank"
                                            class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                            Lihat Bukti Pembayaran
                                        </a>
                                        <span
                                            class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $registration->paymentProofStatus() === 'verified' ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : ($registration->paymentProofStatus() === 'rejected' ? 'border-red-100 bg-red-50 text-red-700' : 'border-amber-100 bg-amber-50 text-amber-700') }}">
                                            {{ $registration->paymentProofStatus() === 'verified' ? 'Pembayaran tervalidasi' : ($registration->paymentProofStatus() === 'rejected' ? 'Bukti pembayaran ditolak' : 'Bukti pembayaran direview') }}
                                        </span>
                                    </div>
                                @endif

                                @if (($registration->document_statuses['payment_proof_path']['note'] ?? null) !== null)
                                    <div
                                        class="mt-4 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                                        {{ $registration->document_statuses['payment_proof_path']['note'] }}
                                    </div>
                                @endif

                                @if (in_array($registration->status, ['dokumen_ok', 'pending_payment'], true))
                                    <form wire:submit="uploadPaymentProof"
                                        class="mt-5 border-t border-amber-100 pt-5">
                                        <label class="block text-sm font-semibold text-slate-800">Upload Bukti
                                            Pembayaran</label>
                                        <div class="mt-3 flex flex-col gap-3 md:flex-row">
                                            <input type="file" wire:model="paymentProof"
                                                class="block w-full text-xs text-slate-500 file:mr-2 file:rounded-md file:border-0 file:bg-white file:px-3 file:py-2 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-100" />
                                            <button type="submit"
                                                class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-black hover:bg-amber-400">
                                                Upload Bukti
                                            </button>
                                        </div>
                                        @error('paymentProof')
                                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </form>
                                @endif
                            </div>
                        @else
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <h3 class="text-base font-bold text-slate-800">Pembayaran belum tersedia</h3>
                                <p class="mt-1.5 text-sm text-slate-500">
                                    Tab pembayaran akan aktif setelah proses verifikasi dokumen selesai.
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
            </section>

            <!-- Riwayat Status Section -->
            <section
                class="rounded-[1.25rem] border border-slate-100 bg-white p-6 md:p-7 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
                <h2 class="text-[1.15rem] font-bold text-slate-800 mb-6">Riwayat Status</h2>

                @if ($statusHistory === [])
                    <p class="text-sm text-slate-500">Belum ada riwayat status.</p>
                @else
                    <div class="space-y-6">
                        @foreach ($statusHistory as $index => $history)
                            <div class="relative flex gap-4">
                                @if (!$loop->last)
                                    <div class="absolute left-[0.9rem] top-8 -bottom-6 w-[2px] bg-slate-100"></div>
                                @endif

                                <div
                                    class="relative z-10 flex h-[1.8rem] w-[1.8rem] shrink-0 items-center justify-center rounded-full {{ $historyBgColors[$history['color']] ?? $historyBgColors['blue'] }}">
                                    <div
                                        class="flex h-[1.15rem] w-[1.15rem] items-center justify-center rounded-full {{ $historyColors[$history['color']] ?? $historyColors['blue'] }} text-white">
                                        <svg class="h-[10px] w-[10px]" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="pb-2">
                                    <p class="text-[14.5px] font-semibold text-slate-800 leading-tight">
                                        {{ $history['title'] }}</p>
                                    <p class="mt-1 text-[13px] text-slate-500 leading-snug">
                                        {{ $history['description'] }}</p>
                                    @if ($history['date'])
                                        <p class="mt-1 text-[12px] font-medium text-slate-400">{{ $history['date'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
