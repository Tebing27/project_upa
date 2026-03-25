<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.verifikasi') }}" wire:navigate
            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-gray-900 transition-all hover:shadow-sm">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Detail Verifikasi Dokumen</h1>
            <p class="mt-1 text-sm text-gray-500">Tinjau kelengkapan berkas dan data diri peserta sertifikasi.</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-8 pb-8 border-b border-gray-50">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $registration->user->name }}</h2>
                        @php
                            $statusColorMap = [
                                'dokumen_ok' => 'bg-teal-50 text-teal-700 border-teal-100',
                                'dokumen_ditolak' => 'bg-red-50 text-red-700 border-red-100',
                                'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'paid' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'terjadwal' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'kompeten' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'tidak_kompeten' => 'bg-red-50 text-red-700 border-red-100',
                            ];
                            $statusClass =
                                $statusColorMap[$registration->status] ?? 'bg-slate-50 text-slate-700 border-slate-100';

                            $statusLabel = str_replace('_', ' ', \Illuminate\Support\Str::title($registration->status));
                            if ($registration->status === 'dokumen_ok') {
                                $statusLabel = 'Draft OK';
                            }
                            if (in_array($registration->status, ['pending_payment', 'paid', 'menunggu_verifikasi'])) {
                                $statusLabel = 'Review';
                            }
                        @endphp
                        <span
                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-3">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">NIM / No.
                                Pendaftaran</span>
                            <span class="mt-1.5 text-sm font-semibold text-gray-900">{{ $registration->user->nim }} /
                                {{ $registration->payment_reference ?: '-' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Program Studi /
                                Skema</span>
                            <span
                                class="mt-1.5 text-sm font-semibold text-gray-900 truncate">{{ $registration->user->program_studi }}
                                / {{ optional($registration->scheme)->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 md:mt-0">
                    @if ($registration->status === 'dokumen_ok')
                        <button wire:click="lanjutkanKeJadwal"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-6 py-3 text-sm font-bold text-black shadow-lg shadow-emerald-100 transition-all hover:bg-emerald-500">
                            Lanjutkan ke Penjadwalan
                        </button>
                    @endif
                </div>
            </div>

            {{-- Biodata Section --}}
            <div class="mb-10">
                <h3 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-400 mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Data Pribadi Peserta
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $biodata = [
                            'Nama Lengkap' => $registration->user->name,
                            'NIM / NIK' => $registration->user->nim ?: '-',
                            'No KTP' => $registration->user->no_ktp ?: '-',
                            'Tempat, Tgl Lahir' =>
                                ($registration->user->tempat_lahir ?: '-') .
                                ', ' .
                                ($registration->user->tanggal_lahir
                                    ? \Carbon\Carbon::parse($registration->user->tanggal_lahir)->translatedFormat(
                                        'd F Y',
                                    )
                                    : '-'),
                            'Jenis Kelamin' =>
                                $registration->user->jenis_kelamin === 'L'
                                    ? 'Laki-laki'
                                    : ($registration->user->jenis_kelamin === 'P'
                                        ? 'Perempuan'
                                        : '-'),
                            'No. WhatsApp' => $registration->user->no_wa ?: '-',
                            'Pendidikan' => $registration->user->pendidikan_terakhir ?: '-',
                            'Fakultas / Prodi' =>
                                ($registration->user->fakultas ?: '-') .
                                ' / ' .
                                ($registration->user->program_studi ?: '-'),
                            'SKS / Semester' =>
                                ($registration->user->total_sks ?: '-') .
                                ' SKS (' .
                                ($registration->user->status_semester ?: '-') .
                                ')',
                        ];
                    @endphp
                    @foreach ($biodata as $label => $value)
                        <div class="flex flex-col p-5 rounded-2xl bg-gray-50/50 border border-gray-50">
                            <span
                                class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">{{ $label }}</span>
                            <span class="text-sm font-semibold text-gray-900 leading-relaxed">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 flex flex-col p-5 rounded-2xl bg-gray-50/50 border border-gray-50">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1.5">Alamat
                        Rumah</span>
                    <span
                        class="text-sm font-semibold text-gray-900 leading-relaxed">{{ $registration->user->alamat_rumah ?: '-' }}</span>
                </div>
            </div>

            <h3 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-400 mb-6">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Dokumen Pendaftaran
            </h3>

            @php
                $docs = [
                    'fr_apl_01_path' => 'Formulir APL-01',
                    'fr_apl_02_path' => 'Formulir APL-02',
                    'ktm_path' => 'KTM',
                    'khs_path' => 'KHS / Transkrip',
                    'internship_certificate_path' => 'Sertifikat Magang',
                    'ktp_path' => 'KTP',
                    'passport_photo_path' => 'Pas Foto 3x4',
                    'payment_reference' => 'Bukti UKT / Pembayaran',
                ];
                $statuses = $registration->document_statuses ?? [];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($docs as $docField => $docName)
                    @php
                        $docData = $statuses[$docField] ?? null;
                        $docStatus = $docData['status'] ?? 'pending';
                        $hasFile = !empty($registration->$docField);
                    @endphp

                    <div
                        class="flex flex-col rounded-2xl border bg-white p-6 transition-all {{ $hasFile ? 'border-gray-100 shadow-sm' : 'border-dashed border-gray-100 bg-gray-50/30' }}">
                        <div class="flex justify-between items-start mb-5">
                            <div class="font-bold text-sm text-gray-900 leading-tight w-2/3">{{ $docName }}
                            </div>
                            @if ($hasFile)
                                @if ($docStatus === 'verified')
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                @elseif($docStatus === 'rejected')
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-red-50 text-red-600">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </span>
                                @else
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                @endif
                            @else
                                <span class="text-xs font-semibold text-gray-400">Kosong</span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            @if ($hasFile)
                                <div class="flex items-center gap-2">
                                    <a href="{{ Storage::url($registration->$docField) }}" target="_blank"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl border border-gray-200 bg-white text-slate-900 hover:bg-gray-50 py-3 text-xs font-semibold">
                                        Lihat
                                    </a>

                                    @if ($docStatus !== 'verified')
                                        <button wire:click="verifikasiDokumen('{{ $docField }}')"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-400 py-3 text-xs font-semibold text-black shadow-sm transition-all hover:bg-emerald-500">
                                            Verifikasi
                                        </button>
                                    @endif

                                    @if ($docStatus !== 'rejected')
                                        <button wire:click="bukaModalTolak('{{ $docField }}')"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-red-600 py-3 text-xs font-semibold text-white shadow-sm transition-all hover:bg-red-700">
                                            Tolak
                                        </button>
                                    @endif
                                </div>
                                @if ($docStatus === 'rejected' && !empty($docData['note']))
                                    <div class="mt-3 rounded-xl bg-red-50 p-3">
                                        <p class="text-xs leading-relaxed text-red-700 font-medium">
                                            <span class="font-bold">Catatan:</span> {{ $docData['note'] }}
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="py-2 text-center text-xs font-medium text-gray-400 italic">Peserta belum
                                    mengunggah dokumen</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal Tolak --}}
    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail.id === 'modal-tolak') show = true"
        x-on:close-modal.window="if ($event.detail.id === 'modal-tolak') show = false"
        x-on:keydown.escape.window="show = false" x-show="show" class="relative z-50" style="display: none;">

        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/40 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95" @click.outside="show = false"
                    class="relative overflow-hidden w-full rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-md">

                    <form wire:submit="tolakDokumen">
                        <div class="p-6 md:p-8">
                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Tolak Dokumen</h3>
                                <p class="text-sm text-gray-500 mt-1">Sampaikan alasan dokumen tidak valid agar peserta
                                    dapat memperbaiki unggahannya.</p>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2">Catatan
                                    Penolakan</label>
                                <textarea wire:model="rejectNote" rows="3"
                                    placeholder="Contoh: Dokumen buram, Nama tidak sesuai, atau File tidak valid."
                                    class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-red-300 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                    required></textarea>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-4 bg-slate-50/50 px-6 py-5 md:px-8 border-t border-slate-100">
                            <button type="button" @click="show = false"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="group relative inline-flex items-center justify-center px-4 py-2.5 font-bold text-white bg-red-600 rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-500/20">
                                <span>Tolak Dokumen</span>
                                <svg class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
