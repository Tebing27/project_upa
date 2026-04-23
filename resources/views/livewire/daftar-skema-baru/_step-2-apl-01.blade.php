<div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
    <div class="mb-8 flex flex-col items-center">
        <div class="flex items-center gap-3">
            @foreach ([1, 2, 3, 4] as $sec)
                <div @class([
                    'relative z-10 flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold ring-4 ring-white transition-all',
                    'bg-emerald-500 text-white' => $apl01SubStep >= $sec,
                    'border-2 border-gray-200 bg-white text-gray-400' => $apl01SubStep < $sec,
                ])>
                    @if ($apl01SubStep > $sec)
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        {{ $sec }}
                    @endif
                </div>
                @if ($sec < 4)
                    <div @class([
                        'h-0.5 w-10 transition-colors',
                        'bg-emerald-500' => $apl01SubStep > $sec,
                        'bg-gray-200' => $apl01SubStep <= $sec,
                    ])></div>
                @endif
            @endforeach
        </div>
        @php
            $sectionTitles = [
                1 => 'Data Pemohon',
                2 => 'Data Sertifikasi',
                3 => 'Bukti Kelengkapan',
                4 => 'Tanda Tangan',
            ];
        @endphp
        <p class="mt-3 text-[10px] font-bold uppercase tracking-widest text-gray-400">
            FR.APL.01 &mdash; Bagian {{ $apl01SubStep }}: {{ $sectionTitles[$apl01SubStep] ?? '' }}
        </p>
    </div>

    @if ($apl01SubStep === 1)
        <div class="mb-8 border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold italic text-gray-900">Bagian 1 : Rincian Data Pemohon Sertifikasi</h3>
            <p class="mt-1 text-xs italic text-gray-400">Pada bagian ini, cantumkan data pribadi, data pendidikan formal, serta data institusi atau perusahaan anda pada saat ini.</p>
        </div>

        <div class="space-y-8">
            <div>
                <h4 class="mb-6 flex items-center gap-2 font-bold text-gray-800">a. Data Pribadi</h4>
                <div class="grid grid-cols-1 items-center gap-x-4 gap-y-5 md:grid-cols-[180px_1fr]">
                    <label class="text-sm font-medium text-gray-600">Nama lengkap</label>
                    <div class="relative">
                        <span class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400">:</span>
                        <input type="text" wire:model="name"
                            class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <label class="text-sm font-medium text-gray-600">No. KTP/NIK/Paspor</label>
                    <div class="relative">
                        <span class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400">:</span>
                        <input type="text" wire:model="no_ktp"
                            class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                        @error('no_ktp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <label class="text-sm font-medium text-gray-600">Tempat / tgl. Lahir</label>
                    <div class="relative grid grid-cols-2 gap-4">
                        <span class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400">:</span>
                        <div>
                            <input type="text" wire:model="tempat_lahir" placeholder="Tempat"
                                class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                            @error('tempat_lahir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <input type="date" wire:model="tanggal_lahir"
                                class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                            @error('tanggal_lahir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <label class="text-sm font-medium text-gray-600">Jenis kelamin</label>
                    <div class="relative flex items-center gap-6">
                        <span class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400">:</span>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input type="radio" wire:model="jenis_kelamin" value="L"
                                    class="text-emerald-500 focus:ring-emerald-500"> Laki-laki
                            </label>
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input type="radio" wire:model="jenis_kelamin" value="P"
                                    class="text-emerald-500 focus:ring-emerald-500"> Wanita
                            </label>
                        </div>
                        <span class="text-[10px] font-normal italic text-gray-400">*Coret yang tidak perlu</span>
                    </div>

                    <label class="text-sm font-medium text-gray-600">Kebangsaan</label>
                    <div class="relative">
                        <span class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400">:</span>
                        <input type="text" wire:model="kebangsaan"
                            class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                    </div>

                    <label class="pt-3 text-sm font-medium text-gray-600">Alamat rumah</label>
                    <div class="relative flex flex-col gap-2 pt-3">
                        <span class="absolute left-[-15px] top-[24px] text-gray-400">:</span>
                        <textarea wire:model="alamat_rumah" rows="2"
                            class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0"></textarea>
                        <div class="flex items-center gap-2 self-end">
                            <label class="text-xs text-gray-500">Kode pos :</label>
                            <input type="text" wire:model="kode_pos_rumah"
                                class="w-24 border-b border-gray-300 bg-transparent px-1 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                        </div>
                        @error('alamat_rumah') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 md:col-span-2">
                        <div class="grid grid-cols-[180px_1fr] items-start gap-x-4 gap-y-5">
                            <label class="pt-1 text-sm font-medium text-gray-600">No. Telepon/E-mail</label>
                            <div class="relative space-y-4">
                                <span class="absolute left-[-15px] top-[5px] text-gray-400">:</span>
                                <div class="grid grid-cols-2 gap-x-8 gap-y-4">
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">Rumah :</label>
                                        <input type="text" wire:model="telp_rumah"
                                            class="grow border-b border-gray-300 bg-transparent px-1 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">Kantor :</label>
                                        <input type="text" wire:model="telp_kantor"
                                            class="grow border-b border-gray-300 bg-transparent px-1 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">HP :</label>
                                        <input type="text" wire:model="no_wa"
                                            class="grow border-b border-gray-300 bg-transparent px-1 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">E-mail :</label>
                                        <input type="email" wire:model="email"
                                            class="grow border-b border-gray-300 bg-transparent px-1 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                                    </div>
                                </div>
                                @error('no_wa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <label class="pt-2 text-sm font-medium text-gray-600">Kualifikasi Pendidikan</label>
                    <div class="relative pt-2">
                        <span class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400">:</span>
                        <input type="text" wire:model="kualifikasi_pendidikan"
                            class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                        <span class="text-[10px] italic text-gray-400">*Coret yang tidak perlu</span>
                        @error('kualifikasi_pendidikan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            <div class="mt-8 border-t border-gray-50 pt-8">
                <h4 class="mb-6 flex items-center gap-2 font-bold text-gray-800">b. Data Institusi / Perusahaan Sekarang</h4>
                <div class="grid grid-cols-1 items-center gap-x-4 gap-y-5 md:grid-cols-[180px_1fr]">
                    <label class="flex flex-col text-sm font-medium text-gray-600">
                        Nama Institusi /
                        <span class="text-xs font-normal">Perusahaan</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400">:</span>
                        <input type="text" wire:model="nama_perusahaan"
                            class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                    </div>

                    <label class="text-sm font-medium text-gray-600">Jabatan</label>
                    <div class="relative">
                        <span class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400">:</span>
                        <input type="text" wire:model="jabatan"
                            class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                    </div>

                    <label class="pt-3 text-sm font-medium text-gray-600">Alamat Kantor</label>
                    <div class="relative flex flex-col gap-2 pt-3">
                        <span class="absolute left-[-15px] top-[24px] text-gray-400">:</span>
                        <textarea wire:model="alamat_perusahaan" rows="2"
                            class="w-full border-b border-gray-300 bg-transparent px-0 py-1 text-sm focus:border-emerald-500 focus:ring-0"></textarea>
                        <div class="flex items-center gap-2 self-end">
                            <label class="text-xs text-gray-500">Kode pos :</label>
                            <input type="text" wire:model="kode_pos_perusahaan"
                                class="w-24 border-b border-gray-300 bg-transparent px-1 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                        </div>
                    </div>

                    <div class="pt-4 md:col-span-2">
                        <div class="grid grid-cols-[180px_1fr] items-start gap-x-4 gap-y-5">
                            <label class="pt-1 text-sm font-medium text-gray-600">No. Telp/Fax/E-mail</label>
                            <div class="relative space-y-4">
                                <span class="absolute left-[-15px] top-[5px] text-gray-400">:</span>
                                <div class="grid grid-cols-2 gap-x-8 gap-y-4">
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">Telp :</label>
                                        <input type="text" wire:model="no_telp_perusahaan"
                                            class="grow border-b border-gray-300 bg-transparent px-1 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">Fax :</label>
                                        <input type="text" wire:model="fax_perusahaan"
                                            class="grow border-b border-gray-300 bg-transparent px-1 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                                    </div>
                                    <div class="md:col-span-2 flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">E-mail :</label>
                                        <input type="email" wire:model="email_perusahaan"
                                            class="grow border-b border-gray-300 bg-transparent px-1 py-1 text-sm focus:border-emerald-500 focus:ring-0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 flex justify-between">
            <button type="button" wire:click="previousStep"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </button>
            <button type="button" wire:click="nextStep"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800">
                Lanjut ke Bagian 2
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    @endif

    @if ($apl01SubStep === 2)
        <div class="mb-8 border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold italic text-gray-900">Bagian 2 : Data Sertifikasi</h3>
            <p class="mt-1 text-xs italic text-gray-400">Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta pengalaman kerja yang anda miliki.</p>
        </div>
        <div class="overflow-hidden rounded-xl border border-gray-300">
            <table class="w-full border-collapse">
                <tbody>
                    <tr>
                        <td rowspan="2"
                            class="w-48 border-r border-b border-gray-300 bg-gray-50 p-4 text-sm font-bold text-gray-700">
                            Skema Sertifikasi (KKNI/Okupasi/Klaster)</td>
                        <td
                            class="w-24 border-r border-b border-gray-300 bg-gray-50 p-3 text-sm font-bold text-gray-700">
                            Judul</td>
                        <td class="border-b border-gray-300 p-4 text-sm italic">:
                            {{ $selectedScheme?->nama }}</td>
                    </tr>
                    <tr>
                        <td
                            class="border-r border-b border-gray-300 bg-gray-50 p-3 text-sm font-bold text-gray-700">
                            Nomor</td>
                        <td class="border-b border-gray-300 p-4 text-sm italic">:
                            {{ $selectedScheme?->kode_skema }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"
                            class="border-r border-b border-gray-300 bg-gray-50 p-4 text-sm font-bold text-gray-700">
                            Tujuan Asesmen</td>
                        <td class="border-b border-gray-300 p-4">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 text-gray-400">:</span>
                                <div class="flex flex-col gap-3">
                                    <label class="inline-flex cursor-pointer items-center gap-3">
                                        <input type="radio" wire:model="assessmentPurpose" value="sertifikasi"
                                            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-sm text-gray-700">Sertifikasi</span>
                                    </label>
                                    <label class="inline-flex cursor-pointer items-center gap-3">
                                        <input type="radio" wire:model="assessmentPurpose" value="paling_lambat_pkt"
                                            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-sm text-gray-700">Pengakuan Kompetensi Terkini (PKT)</span>
                                    </label>
                                    <label class="inline-flex cursor-pointer items-center gap-3">
                                        <input type="radio" wire:model="assessmentPurpose" value="rpl"
                                            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-sm text-gray-700">Rekognisi Pembelajaran Lampau (RPL)</span>
                                    </label>
                                    <label class="inline-flex cursor-pointer items-center gap-3">
                                        <input type="radio" wire:model="assessmentPurpose" value="lainnya"
                                            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-sm text-gray-700">Lainnya</span>
                                    </label>
                                </div>
                            </div>
                            @error('assessmentPurpose') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if ($selectedScheme && $selectedScheme->unitKompetensis->isNotEmpty())
            <div class="mt-8">
                <p class="mb-2 text-xs font-bold italic text-gray-800">Daftar Unit Kompetensi sesuai kemasan:</p>
                <div class="overflow-x-auto rounded-xl border border-gray-300">
                    <table class="w-full text-center text-xs">
                        <thead class="border-b border-gray-300 bg-gray-50 font-bold">
                            <tr>
                                <td class="w-12 border-r border-gray-300 p-2">No.</td>
                                <td class="w-48 border-r border-gray-300 p-2">Kode Unit</td>
                                <td class="border-r border-gray-300 p-2">Judul Unit</td>
                                <td class="w-48 p-2">Standar Kompetensi Kerja</td>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 italic">
                            @foreach ($selectedScheme->unitKompetensis as $unit)
                                <tr>
                                    <td class="border-r border-gray-300 p-2">{{ $loop->iteration }}.</td>
                                    <td class="border-r border-gray-300 p-2">{{ $unit->kode_unit }}</td>
                                    <td class="border-r border-gray-300 p-2 text-left">{{ $unit->nama_unit }}</td>
                                    <td class="p-2">{{ $unit->standar }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="mt-10 flex justify-between">
            <button type="button" wire:click="previousStep"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </button>
            <button type="button" wire:click="nextStep"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800">
                Lanjut ke Bagian 3
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    @endif

    @if ($apl01SubStep === 3)
        <div class="mb-4 border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold italic text-gray-900">Bagian 3 : Bukti Kelengkapan Pemohon</h3>
        </div>
        <div class="overflow-x-auto rounded-xl border border-gray-300">
            <table class="w-full text-xs">
                <thead class="border-b border-gray-300 bg-gray-50 font-bold">
                    <tr>
                        <td class="w-12 border-r border-gray-300 p-3 text-center">No.</td>
                        <td class="border-r border-gray-300 p-3">Bukti Persyaratan Dasar</td>
                        <td class="w-72 p-3">Upload / Status</td>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    <tr>
                        <td class="border-r border-gray-300 p-4 text-center">1.</td>
                        <td class="border-r border-gray-300 p-4 italic">{{ \App\Models\Registration::apl01RequirementLabels()['ktm_path'] }}</td>
                        <td class="space-y-2 p-4">
                            <input type="file" wire:model="ktm"
                                class="w-full text-[10px] file:rounded file:border-0 file:bg-gray-100 file:px-2 file:py-1">
                            <p class="text-[11px] {{ $ktm ? 'text-emerald-600' : 'text-gray-500' }}">{{ $ktm ? 'Sudah dipilih' : 'Belum upload' }}</p>
                            @error('ktm') <p class="text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td class="border-r border-gray-300 p-4 text-center">2.</td>
                        <td class="border-r border-gray-300 p-4 italic">{{ \App\Models\Registration::apl01RequirementLabels()['khs_path'] }}</td>
                        <td class="space-y-2 p-4">
                            <input type="file" wire:model="khs"
                                class="w-full text-[10px] file:rounded file:border-0 file:bg-gray-100 file:px-2 file:py-1">
                            <p class="text-[11px] {{ $khs ? 'text-emerald-600' : 'text-gray-500' }}">{{ $khs ? 'Sudah dipilih' : 'Belum upload' }}</p>
                            @error('khs') <p class="text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td class="border-r border-gray-300 p-4 text-center">3.</td>
                        <td class="border-r border-gray-300 p-4 italic">{{ \App\Models\Registration::apl01RequirementLabels()['internship_certificate_path'] }}</td>
                        <td class="space-y-2 p-4">
                            <input type="file" wire:model="internshipCertificate"
                                class="w-full text-[10px] file:rounded file:border-0 file:bg-gray-100 file:px-2 file:py-1">
                            <p class="text-[11px] {{ $internshipCertificate ? 'text-emerald-600' : 'text-gray-500' }}">{{ $internshipCertificate ? 'Sudah dipilih' : 'Opsional' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-r border-gray-300 p-4 text-center">4.</td>
                        <td class="border-r border-gray-300 p-4 italic">{{ \App\Models\Registration::apl01RequirementLabels()['ktp_path'] }}</td>
                        <td class="space-y-2 p-4">
                            <input type="file" wire:model="ktp"
                                class="w-full text-[10px] file:rounded file:border-0 file:bg-gray-100 file:px-2 file:py-1">
                            <p class="text-[11px] {{ $ktp ? 'text-emerald-600' : 'text-gray-500' }}">{{ $ktp ? 'Sudah dipilih' : 'Belum upload' }}</p>
                            @error('ktp') <p class="text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td class="border-r border-gray-300 p-4 text-center">5.</td>
                        <td class="border-r border-gray-300 p-4 italic">{{ \App\Models\Registration::apl01RequirementLabels()['passport_photo_path'] }}</td>
                        <td class="space-y-2 p-4">
                            <input type="file" wire:model="passportPhoto"
                                class="w-full text-[10px] file:rounded file:border-0 file:bg-gray-100 file:px-2 file:py-1">
                            <p class="text-[11px] {{ $passportPhoto ? 'text-emerald-600' : 'text-gray-500' }}">{{ $passportPhoto ? 'Sudah dipilih' : 'Belum upload' }}</p>
                            @error('passportPhoto') <p class="text-[10px] text-red-500">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-10 flex justify-between">
            <button type="button" wire:click="previousStep"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </button>
            <button type="button" wire:click="confirmApl01"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800">
                Lanjut ke Bagian 4
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    @endif

    @if ($apl01SubStep === 4)
        <div class="mb-6 border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold italic text-gray-900">Bagian 4 : Rekomendasi & Tanda Tangan</h3>
            <p class="mt-1 text-xs italic text-gray-400">Berikan tanda tangan digital Anda sebagai pernyataan bahwa data yang dimasukkan adalah benar.</p>
        </div>

        <div class="overflow-hidden rounded-xl border border-stone-400 bg-[#fffdf8] shadow-sm">
            <table class="w-full table-fixed border-collapse text-sm">
                <tbody>
                    <tr>
                        <td class="w-[52%] border-r border-b border-stone-400 p-4 align-top">
                            <p class="text-[13px] font-bold text-stone-800">Rekomendasi (diisi oleh LSP):</p>
                            <p class="mt-1 text-[13px] leading-6 text-stone-700">Berdasarkan ketentuan persyaratan dasar, maka pemohon:</p>
                            <p class="mt-2 text-[13px] font-semibold text-stone-800">Diterima / Tidak diterima *) sebagai peserta sertifikasi</p>
                            <p class="mt-1 text-[11px] italic text-stone-500">* coret yang tidak sesuai</p>
                        </td>
                        <td class="border-b border-stone-400 p-0 align-top">
                            <table class="w-full table-fixed border-collapse text-sm">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="border-b border-stone-400 px-4 py-3 text-[13px] font-bold text-stone-800">Pemohon / Kandidat :</td>
                                    </tr>
                                    <tr>
                                        <td class="w-[34%] border-r border-b border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">Nama</td>
                                        <td class="border-b border-stone-400 px-4 py-3 align-top">
                                            <p class="text-[13px] font-medium text-stone-900">{{ $name ?: 'Belum diisi' }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-r border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                            Tanda tangan/
                                            <br>
                                            Tanggal
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <div
                                                x-data="{
                                                    signaturePad: null,
                                                    resizeHandler: null,
                                                    init() {
                                                        const canvas = this.$refs.signatureCanvas;
                                                        const SignaturePadLib = window.SignaturePad;

                                                        if (! SignaturePadLib) {
                                                            return;
                                                        }

                                                        this.resizeHandler = () => {
                                                            const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                                            const currentValue = $wire.applicantSignature;

                                                            canvas.width = canvas.offsetWidth * ratio;
                                                            canvas.height = canvas.offsetHeight * ratio;
                                                            canvas.getContext('2d').scale(ratio, ratio);

                                                            if (! this.signaturePad) {
                                                                return;
                                                            }

                                                            this.signaturePad.clear();

                                                            if (currentValue) {
                                                                this.signaturePad.fromDataURL(currentValue, { ratio });
                                                            }
                                                        };

                                                        this.signaturePad = new SignaturePadLib(canvas, {
                                                            backgroundColor: 'rgb(255, 253, 248)',
                                                            penColor: 'rgb(68, 64, 60)',
                                                        });

                                                        this.resizeHandler();
                                                        window.addEventListener('resize', this.resizeHandler);

                                                        this.signaturePad.addEventListener('endStroke', () => {
                                                            $wire.set('applicantSignature', this.signaturePad.toDataURL('image/png'));
                                                        });
                                                    },
                                                    clearSignature() {
                                                        if (! this.signaturePad) {
                                                            return;
                                                        }

                                                        this.signaturePad.clear();
                                                        $wire.set('applicantSignature', null);
                                                    },
                                                    destroy() {
                                                        if (this.resizeHandler) {
                                                            window.removeEventListener('resize', this.resizeHandler);
                                                        }
                                                    }
                                                }"
                                                x-init="init()"
                                            >
                                                <div class="rounded-lg border border-dashed border-stone-300 bg-stone-50/80 p-2">
                                                    <canvas
                                                        x-ref="signatureCanvas"
                                                        class="h-28 w-full cursor-crosshair rounded-md bg-transparent"
                                                        style="touch-action: none;"
                                                    ></canvas>
                                                </div>
                                                <div class="mt-2 flex items-center justify-between gap-4">
                                                    <p class="text-[11px] italic text-stone-500">Tanda tangan pakai mouse / touchpad / layar sentuh.</p>
                                                    <button
                                                        type="button"
                                                        @click="clearSignature()"
                                                        class="text-[11px] font-semibold text-red-600 transition hover:text-red-700"
                                                    >
                                                        Hapus
                                                    </button>
                                                </div>
                                                <p class="mt-2 text-[12px] text-stone-600">{{ now()->translatedFormat('d F Y') }}</p>
                                                @error('applicantSignature')
                                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-r border-stone-400 p-4 align-top">
                            <p class="text-[13px] font-bold text-stone-800">Catatan :</p>
                            <div class="mt-2 min-h-28 rounded-md border border-dashed border-stone-300 bg-white/70 p-3 text-[12px] italic text-stone-500">
                                Menunggu hasil verifikasi admin LSP.
                            </div>
                        </td>
                        <td class="p-0 align-top">
                            <table class="w-full table-fixed border-collapse text-sm">
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="border-b border-stone-400 px-4 py-3 text-[13px] font-bold text-stone-800">Admin LSP :</td>
                                    </tr>
                                    <tr>
                                        <td class="w-[34%] border-r border-b border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">Nama :</td>
                                        <td class="border-b border-stone-400 px-4 py-3 align-top">
                                            <p class="text-[13px] {{ $adminVerificationPreview['state'] === 'verified' ? 'font-medium text-stone-900' : 'italic text-stone-400' }}">
                                                {{ $adminVerificationPreview['state'] === 'verified' ? ($adminVerificationPreview['name'] ?: 'Admin LSP') : 'Menunggu verifikasi admin LSP' }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-r border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                            Tanda tangan/
                                            <br>
                                            Tanggal
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            @if ($adminVerificationPreview['state'] === 'verified')
                                                <div class="rounded-md border border-emerald-200 bg-emerald-50/70 px-3 py-4">
                                                    <p class="text-[13px] font-semibold text-emerald-800">Terverifikasi admin LSP</p>
                                                    <p class="mt-1 text-[12px] text-emerald-700">{{ $adminVerificationPreview['date'] ?: 'Tanggal verifikasi belum tersedia' }}</p>
                                                </div>
                                            @elseif ($adminVerificationPreview['state'] === 'rejected')
                                                <div class="rounded-md border border-red-200 bg-red-50/70 px-3 py-4">
                                                    <p class="text-[13px] font-semibold text-red-700">Verifikasi tidak disetujui</p>
                                                    <p class="mt-1 text-[12px] text-red-600">Tanda tangan admin tidak ditampilkan.</p>
                                                </div>
                                            @else
                                                <div class="rounded-md border border-dashed border-stone-300 bg-stone-50/80 px-3 py-4">
                                                    <p class="text-[13px] italic text-stone-500">Menunggu verifikasi admin LSP.</p>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-10 flex justify-between">
            <button type="button" wire:click="previousStep"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </button>
            <button type="button" wire:click="nextStep"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-600">
                Selesaikan APL 01
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </button>
        </div>
    @endif
</div>
