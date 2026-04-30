<div class="flex flex-col gap-4 border-b border-slate-100 pb-5 md:flex-row md:items-start md:justify-between">
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
                Biodata tetap bisa dicek kapan saja. Perubahan hanya dibuka ketika ada dokumen yang
                ditolak admin.
            @elseif ($activeTab === 'dokumen')
                Pantau hasil review dokumen, lihat catatan admin, dan upload ulang jika diperlukan.
            @elseif ($activeTab === 'tanda_tangan')
                Pantau tanda tangan pemohon dan finalisasi verifikasi admin pada tahap ini.
            @elseif ($activeTab === 'pembayaran')
                Kelola instruksi pembayaran dan upload bukti pembayaran pada tahap ini.
            @else
                Detail ujian ditampilkan lebih ringkas agar peserta langsung melihat jadwal, lokasi, dan
                akses koordinasi.
            @endif
        </p>
    </div>

    @if ($canEditBiodata && !$isEditingBiodata && $activeTab === 'biodata')
        <button type="button" wire:click="startEditingBiodata" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
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
        @if (in_array($registration->status, ['terjadwal', 'sertifikat_terbit', 'tidak_kompeten'], true) &&
                $registration->exam_date)
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
