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

        $identityLabel = $registration?->user?->isGeneralUser() ? 'NIK' : 'NIM';
        $identityValue = $registration?->user?->isGeneralUser()
            ? ($registration?->user?->no_ktp ?: '-')
            : ($registration?->user?->nim ?: '-');
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">
        @if (!$registration)
            @include('livewire.user-registration-status._empty-state')

            @php return; @endphp
        @endif

        @include('livewire.user-registration-status._header')
        @include('livewire.user-registration-status._progress-card')

        @php
            $biodataItems = [
                'Nama Lengkap' => $registration->user->name ?: '-',
                'Email' => $registration->user->email ?: '-',
                'NIK' => $registration->user->no_ktp ?: '-',
                'Tempat Lahir' => $registration->user->tempat_lahir ?: '-',
                'Tanggal Lahir' => $registration->user->tanggal_lahir
                    ? \Carbon\Carbon::parse($registration->user->tanggal_lahir)->translatedFormat('d M Y')
                    : '-',
                'Jenis Kelamin' =>
                    $registration->user->jenis_kelamin === 'L'
                        ? 'Laki-laki'
                        : ($registration->user->jenis_kelamin === 'P'
                            ? 'Perempuan'
                            : '-'),
                'Nomor WhatsApp' => $registration->user->no_wa ?: '-',
                'Alamat Rumah' => $registration->user->alamat_rumah ?: '-',
                'Kode Pos' => $registration->user->profile?->kode_pos_rumah ?: '-',
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
            ];
        @endphp

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr] xl:grid-cols-[1.8fr_1fr]">
            <!-- Detail Section -->
            <section
                class="rounded-[1.25rem] border border-slate-100 bg-white p-5 sm:p-6 md:p-7 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
                @include('livewire.user-registration-status._detail-tabs-header')

                @if ($activeTab === 'biodata')
                    @if ($canEditBiodata)
                        <div
                            class="mt-6 rounded-[1.15rem] border border-amber-200 bg-gradient-to-r from-amber-50 to-white p-4">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-amber-900">Ada dokumen yang ditolak admin</p>
                                    <p class="mt-1 text-sm leading-relaxed text-amber-800">
                                        Supaya perbaikan terasa jelas, kami membuka edit biodata di tab ini. Perbarui
                                        data yang
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
                                <div><label class="block text-sm font-semibold text-slate-700">Nama
                                        Lengkap</label><input type="text" wire:model="profile.nama"
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
                                <div><label class="block text-sm font-semibold text-slate-700">NIK</label><input
                                        type="text" wire:model="profile.no_ktp"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.no_ktp')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Tempat
                                        Lahir</label><input type="text" wire:model="profile.tempat_lahir"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.tempat_lahir')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Tanggal
                                        Lahir</label><input type="date" wire:model="profile.tanggal_lahir"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.tanggal_lahir')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Jenis
                                        Kelamin</label><select wire:model="profile.jenis_kelamin"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('profile.jenis_kelamin')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Nomor
                                        WhatsApp</label><input type="text" wire:model="profile.no_wa"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.no_wa')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2"><label
                                        class="block text-sm font-semibold text-slate-700">Alamat
                                        Rumah</label>
                                    <textarea wire:model="profile.alamat_rumah" rows="3"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"></textarea>
                                    @error('profile.alamat_rumah')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Kode Pos</label><input
                                        type="text" wire:model="profile.kode_pos_rumah"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.kode_pos_rumah')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Kualifikasi
                                        Pendidikan</label><input type="text"
                                        wire:model="profile.kualifikasi_pendidikan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.kualifikasi_pendidikan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Telepon
                                        Rumah</label><input type="text" wire:model="profile.telp_rumah"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.telp_rumah')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Telepon
                                        Kantor</label><input type="text" wire:model="profile.telp_kantor"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.telp_kantor')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Nama Institusi /
                                        Perusahaan</label><input type="text"
                                        wire:model="profile.nama_perusahaan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.nama_perusahaan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label
                                        class="block text-sm font-semibold text-slate-700">Jabatan</label><input
                                        type="text" wire:model="profile.jabatan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.jabatan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Kode Pos
                                        Perusahaan</label><input type="text"
                                        wire:model="profile.kode_pos_perusahaan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.kode_pos_perusahaan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Telepon
                                        Perusahaan</label><input type="text"
                                        wire:model="profile.no_telp_perusahaan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.no_telp_perusahaan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div><label class="block text-sm font-semibold text-slate-700">Email
                                        Perusahaan</label><input type="email"
                                        wire:model="profile.email_perusahaan"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                    @error('profile.email_perusahaan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="md:col-span-2"><label
                                        class="block text-sm font-semibold text-slate-700">Alamat
                                        Perusahaan</label>
                                    <textarea wire:model="profile.alamat_perusahaan" rows="3"
                                        class="mt-2 block w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"></textarea>
                                    @error('profile.alamat_perusahaan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
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
                        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
                            @foreach ($biodataItems as $label => $value)
                                <article class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                        {{ $label }}</p>
                                    <p class="mt-2 text-sm font-semibold leading-relaxed text-slate-800">
                                        {{ $value }}
                                    </p>
                                </article>
                            @endforeach
                        </div>
                    @endif
                @elseif ($activeTab === 'jadwal')
                    @include('livewire.user-registration-status._jadwal-tab')
                @elseif ($activeTab === 'dokumen')
                    <div class="mt-6">
                        @if ($canEditBiodata)
                            <div
                                class="mb-4 rounded-[1.15rem] border border-blue-200 bg-gradient-to-r from-blue-50 to-white p-4">
                                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-blue-900">Biodata masih bisa diperbarui
                                        </p>
                                        <p class="mt-1 text-sm leading-relaxed text-blue-800">
                                            Jika revisi dokumen berkaitan dengan data peserta, buka tab biodata untuk
                                            memperbarui informasi sebelum upload ulang dokumen.
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
                            <div class="overflow-hidden rounded-[1.15rem] border border-slate-200 overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="w-16 px-4 py-3 text-left font-semibold text-slate-600">No.</th>
                                            <th class="px-4 py-3 text-left font-semibold text-slate-600">Bukti
                                                Persyaratan Dasar</th>
                                            <th
                                                class="w-auto lg:w-[22rem] px-4 py-3 text-left font-semibold text-slate-600">
                                                Upload / Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 bg-white">
                                        @foreach ($documentCards as $document)
                                            <tr
                                                class="{{ $statusStyles[$document['status']] ?? $statusStyles['missing'] }}">
                                                <td class="px-4 py-4 align-top font-semibold text-slate-500">
                                                    {{ $loop->iteration }}.</td>
                                                <td class="px-4 py-4 align-top">
                                                    <p class="text-[14px] font-semibold text-slate-800">
                                                        {{ $document['label'] }}</p>
                                                </td>
                                                <td class="px-4 py-4 align-top">
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $statusIconBgs[$document['status']] ?? $statusIconBgs['missing'] }}">
                                                                <svg class="h-5 w-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="1.8"
                                                                        d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h7.586A2 2 0 0116 4.586L19.414 8A2 2 0 0120 9.414V18a2 2 0 01-2 2z" />
                                                                </svg>
                                                            </div>
                                                            <div
                                                                class="flex items-center gap-1.5 {{ $statusTextColors[$document['status']] ?? $statusTextColors['missing'] }}">
                                                                @if ($document['status'] === 'verified')
                                                                    <svg class="h-[13px] w-[13px]" viewBox="0 0 20 20"
                                                                        fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                @elseif($document['status'] === 'rejected')
                                                                    <svg class="h-[13px] w-[13px]" viewBox="0 0 20 20"
                                                                        fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                @else
                                                                    <svg class="h-[13px] w-[13px]" viewBox="0 0 20 20"
                                                                        fill="currentColor">
                                                                        <path fill-rule="evenodd"
                                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                @endif
                                                                <span
                                                                    class="text-[12px] font-medium">{{ $statusLabels[$document['status']] ?? 'Belum diketahui' }}</span>
                                                            </div>
                                                        </div>

                                                        @if ($document['note'])
                                                            <p class="text-[13px] text-slate-600">
                                                                {{ $document['note'] }}</p>
                                                        @endif

                                                        @if ($document['has_file'] && $document['file_url'])
                                                            <a href="{{ $document['file_url'] }}" target="_blank"
                                                                class="inline-flex w-fit items-center rounded-full border border-[#a7f3d0]/60 bg-white px-3 py-1 text-[12px] font-medium text-[#059669] transition hover:bg-emerald-50">
                                                                Lihat File
                                                            </a>
                                                        @else
                                                            <span
                                                                class="inline-flex w-fit items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-[12px] font-medium text-slate-400">
                                                                Belum Ada File
                                                            </span>
                                                        @endif

                                                        @if ($document['can_upload_optional'])
                                                            <form
                                                                wire:submit="reuploadDocument('{{ $document['field'] }}')"
                                                                class="border-t border-slate-200/60 pt-3">
                                                                <label
                                                                    class="block text-xs font-semibold tracking-wider text-slate-500">
                                                                    Jika sudah memiliki, Anda bisa upload!
                                                                </label>
                                                                <div
                                                                    class="mt-3 flex flex-col gap-2 md:flex-row md:items-stretch">
                                                                    <div class="relative flex-1 group">
                                                                        <input type="file"
                                                                            wire:model="reuploadFiles.{{ $document['field'] }}"
                                                                            class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" />
                                                                        <div @class([
                                                                            'flex h-full min-h-[44px] items-center gap-2.5 rounded-lg border-2 border-dashed px-3 py-1.5 transition',
                                                                            'border-slate-200 bg-slate-50 group-hover:border-slate-400 group-hover:bg-slate-100' => !isset(
                                                                                $reuploadFiles[$document['field']]),
                                                                            'border-emerald-200 bg-emerald-50/30' => isset(
                                                                                $reuploadFiles[$document['field']]),
                                                                        ])>
                                                                            <div
                                                                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded bg-white shadow-sm ring-1 ring-slate-200">
                                                                                <svg class="h-3.5 w-3.5 text-slate-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                                                </svg>
                                                                            </div>
                                                                            <div class="flex-1 truncate text-left">
                                                                                <p
                                                                                    class="truncate text-[12px] font-semibold text-slate-700">
                                                                                    {{ isset($reuploadFiles[$document['field']]) ? $reuploadFiles[$document['field']]->getClientOriginalName() : 'Pilih File...' }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="shrink-0 rounded-lg bg-slate-800 px-4 py-2 text-[13px] font-bold text-white shadow-sm transition hover:bg-slate-700 disabled:opacity-50">
                                                                        Upload
                                                                    </button>
                                                                </div>
                                                                @error('reuploadFiles.' . $document['field'])
                                                                    <p class="mt-1.5 text-xs text-red-600">
                                                                        {{ $message }}</p>
                                                                @enderror
                                                            </form>
                                                        @endif

                                                        @if ($document['can_reupload'])
                                                            <form
                                                                wire:submit="reuploadDocument('{{ $document['field'] }}')"
                                                                class="border-t border-slate-200/60 pt-3">
                                                                <div
                                                                    class="mt-3 flex flex-col gap-2 md:flex-row md:items-stretch">
                                                                    <div class="relative flex-1 group">
                                                                        <input type="file"
                                                                            wire:model="reuploadFiles.{{ $document['field'] }}"
                                                                            class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" />
                                                                        <div @class([
                                                                            'flex h-full min-h-[44px] items-center gap-2.5 rounded-lg border-2 border-dashed px-3 py-1.5 transition',
                                                                            'border-slate-200 bg-slate-50 group-hover:border-slate-400 group-hover:bg-slate-100' => !isset(
                                                                                $reuploadFiles[$document['field']]),
                                                                            'border-emerald-200 bg-emerald-50/30' => isset(
                                                                                $reuploadFiles[$document['field']]),
                                                                        ])>
                                                                            <div
                                                                                class="flex h-6 w-6 shrink-0 items-center justify-center rounded bg-white shadow-sm ring-1 ring-slate-200">
                                                                                <svg class="h-3.5 w-3.5 text-slate-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                                                </svg>
                                                                            </div>
                                                                            <div class="flex-1 truncate text-left">
                                                                                <p
                                                                                    class="truncate text-[12px] font-semibold text-slate-700">
                                                                                    {{ isset($reuploadFiles[$document['field']]) ? $reuploadFiles[$document['field']]->getClientOriginalName() : 'Pilih File...' }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="shrink-0 rounded-lg bg-slate-800 px-4 py-2 text-[13px] font-bold text-white shadow-sm transition hover:bg-slate-700 disabled:opacity-50">
                                                                        Upload
                                                                    </button>
                                                                </div>
                                                                @error('reuploadFiles.' . $document['field'])
                                                                    <p class="mt-1.5 text-xs text-red-600">
                                                                        {{ $message }}</p>
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
                            <div
                                class="rounded-[1.25rem] border border-emerald-100 bg-gradient-to-r from-emerald-50 via-white to-white p-5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Tanda Tangan
                                    Verifikator</p>
                                <h3 class="mt-2 text-lg font-bold text-slate-800">
                                    {{ $registration->admin_signatory_name ?: '-' }}</h3>
                                <p class="mt-1 text-sm text-slate-500">Semua dokumen telah diverifikasi admin.</p>

                                @if ($registration->admin_signature_path)
                                    <div
                                        class="mt-5 overflow-hidden rounded-2xl border border-emerald-100 bg-white p-4">
                                        <img src="{{ Storage::url($registration->admin_signature_path) }}"
                                            alt="Tanda tangan admin" class="h-40 w-full object-contain">
                                    </div>
                                @endif
                            </div>
                        @else
                            <div
                                class="rounded-[1.25rem] border border-amber-100 bg-gradient-to-r from-amber-50 via-white to-white p-5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Tanda Tangan
                                    Pemohon</p>
                                <h3 class="mt-2 text-lg font-bold text-slate-800">
                                    {{ $registration->user->name ?: '-' }}</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Tanda tangan admin akan tampil di tab ini setelah seluruh dokumen selesai
                                    diverifikasi.
                                </p>

                                @if ($registration->applicant_signature_path)
                                    <div class="mt-5 overflow-hidden rounded-2xl border border-amber-100 bg-white p-4">
                                        <img src="{{ Storage::url($registration->applicant_signature_path) }}"
                                            alt="Tanda tangan pemohon" class="h-40 w-full object-contain">
                                    </div>
                                @else
                                    <div
                                        class="mt-5 rounded-2xl border border-dashed border-slate-200 bg-white p-5 text-sm text-slate-500">
                                        Tanda tangan pemohon belum tersedia.
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($registration->applicant_signature_path && $this->shouldDisplayAdminSignature())
                            <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Arsip Tanda
                                    Tangan Pemohon</p>
                                <div class="mt-4 overflow-hidden rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <img src="{{ Storage::url($registration->applicant_signature_path) }}"
                                        alt="Arsip tanda tangan pemohon" class="h-32 w-full object-contain">
                                </div>
                            </div>

                            <div class="rounded-[1.25rem] border border-slate-200 bg-slate-50 p-5">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Unduh Dokumen
                                </p>
                                <h3 class="mt-2 text-lg font-bold text-slate-800">FR.APL.01 Final</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    File PDF ini tersedia setelah seluruh dokumen diverifikasi dan tanda tangan admin
                                    LSP ditempelkan ke pendaftaran Anda.
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
                                        <div class="mt-3 flex flex-col gap-3 md:flex-row md:items-stretch">
                                            <div class="relative flex-1 group">
                                                <input type="file" wire:model="paymentProof" accept=".pdf,.jpg,.jpeg,.png"
                                                    class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" />
                                                <div @class([
                                                    'flex h-full min-h-[56px] items-center gap-3 rounded-xl border-2 border-dashed px-4 py-2.5 transition',
                                                    'border-amber-200 bg-white group-hover:border-amber-400 group-hover:bg-amber-50/30' => !$paymentProof,
                                                    'border-emerald-200 bg-emerald-50/30' => $paymentProof,
                                                ])>
                                                    <div
                                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
                                                        <svg class="h-4 w-4 text-slate-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 truncate text-left">
                                                        <p class="truncate text-[13px] font-semibold text-slate-700">
                                                            {{ $paymentProof ? $paymentProof->getClientOriginalName() : 'Pilih File Bukti...' }}
                                                        </p>
                                                        <p
                                                            class="text-[11px] font-medium uppercase tracking-wider text-slate-400">
                                                            PDF, JPG, PNG (Maks 2MB)</p>
                                                    </div>
                                                    @if ($paymentProof)
                                                        <div class="shrink-0">
                                                            <svg class="h-5 w-5 text-emerald-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <button type="submit"
                                                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-black shadow-sm transition hover:bg-amber-400 disabled:cursor-not-allowed disabled:opacity-50">
                                                <span wire:loading.remove wire:target="uploadPaymentProof">Upload
                                                    Bukti</span>
                                                <span wire:loading
                                                    wire:target="uploadPaymentProof">Mengupload...</span>
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

            @include('livewire.user-registration-status._status-history')
        </div>
    </div>
</div>

@include('livewire.user-registration-status._auto-scroll-script')
