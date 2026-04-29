<div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
    <div class="mb-8 flex flex-col items-center">
        <div class="flex items-center gap-1.5 sm:gap-3">
            @foreach ([1, 2, 3, 4] as $sec)
                <div @class([
                    'relative z-10 flex h-7 w-7 sm:h-8 sm:w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold ring-4 ring-white transition-all',
                    'bg-emerald-500 text-white' => $apl01SubStep >= $sec,
                    'border-2 border-gray-200 bg-white text-gray-400' => $apl01SubStep < $sec,
                ])>
                    @if ($apl01SubStep > $sec)
                        <svg class="h-3 w-3 sm:h-3.5 sm:w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        {{ $sec }}
                    @endif
                </div>
                @if ($sec < 4)
                    <div @class([
                        'h-0.5 w-6 sm:w-10 shrink-0 transition-colors',
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
            <p class="mt-1 text-xs italic text-gray-400">Pada bagian ini, cantumkan data pribadi, data pendidikan
                formal, serta data institusi atau perusahaan anda pada saat ini.</p>
        </div>

        <div class="space-y-8">
            <div>
                <h4 class="mb-6 flex items-center gap-2 font-bold text-gray-800">a. Data Pribadi</h4>
                <div
                    class="grid grid-cols-1 items-start gap-x-4 gap-y-5 sm:grid-cols-[140px_1fr] md:grid-cols-[180px_1fr] lg:grid-cols-[200px_1fr] xl:gap-x-6">
                    <label class="text-sm font-medium text-gray-600 sm:pt-2">Nama lengkap <span
                            class="text-red-500">*</span></label>
                    <div class="relative group">
                        <span
                            class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                        <input type="text" wire:model="name"
                            class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="text-sm font-medium text-gray-600 sm:pt-2">No. KTP/NIK/Paspor <span
                            class="text-red-500">*</span></label>
                    <div class="relative group">
                        <span
                            class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                        <input type="text" wire:model="no_ktp"
                            class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                        @error('no_ktp')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="text-sm font-medium text-gray-600 sm:pt-2">Tempat / tgl. Lahir <span
                            class="text-red-500">*</span></label>
                    <div class="relative grid grid-cols-1 sm:grid-cols-2 gap-4 group">
                        <span
                            class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                        <div>
                            <input type="text" wire:model="tempat_lahir" placeholder="Tempat"
                                class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                            @error('tempat_lahir')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="date" wire:model="tanggal_lahir"
                                class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                            @error('tanggal_lahir')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <label class="text-sm font-medium text-gray-600 sm:pt-2">Jenis kelamin <span
                            class="text-red-500">*</span></label>
                    <div class="relative flex items-center gap-6 group sm:pt-2">
                        <span
                            class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
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

                    </div>

                    <label class="text-sm font-medium text-gray-600 sm:pt-2">Kebangsaan</label>
                    <div class="relative group">
                        <span
                            class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                        <input type="text" wire:model="kebangsaan"
                            class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                    </div>

                    <label class="pt-3 text-sm font-medium text-gray-600 sm:pt-2">Alamat rumah <span
                            class="text-red-500">*</span></label>
                    <div class="relative flex flex-col gap-2 pt-3 sm:pt-0 group">
                        <span
                            class="absolute left-[-15px] top-[12px] text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                        <textarea wire:model="alamat_rumah" rows="2"
                            class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors"></textarea>
                        <div class="flex items-center gap-2 self-end">
                            <label class="text-xs text-gray-500">Kode pos <span class="text-red-500">*</span> :</label>
                            <input type="text" wire:model="kode_pos_rumah"
                                class="w-24 border-0 border-b-2 border-slate-200 bg-transparent px-1 py-1 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                        </div>
                        @error('alamat_rumah')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        @error('kode_pos_rumah')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 sm:col-span-2">
                        <div
                            class="grid grid-cols-1 sm:grid-cols-[140px_1fr] md:grid-cols-[180px_1fr] lg:grid-cols-[200px_1fr] xl:gap-x-6 items-start gap-x-4 gap-y-5">
                            <label class="pt-1 text-sm font-medium text-gray-600 sm:pt-2">No. Telepon/E-mail <span
                                    class="text-red-500">*</span></label>
                            <div class="relative space-y-4 group">
                                <span
                                    class="absolute left-[-15px] top-[12px] text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">Rumah :</label>
                                        <input type="text" wire:model="telp_rumah"
                                            class="grow border-0 border-b-2 border-slate-200 bg-transparent px-1 py-1 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">Kantor :</label>
                                        <input type="text" wire:model="telp_kantor"
                                            class="grow border-0 border-b-2 border-slate-200 bg-transparent px-1 py-1 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">HP <span
                                                class="text-red-500">*</span> :</label>
                                        <input type="text" wire:model="no_wa"
                                            class="grow border-0 border-b-2 border-slate-200 bg-transparent px-1 py-1 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">E-mail <span
                                                class="text-red-500">*</span> :</label>
                                        <input type="email" wire:model="email"
                                            class="grow border-0 border-b-2 border-slate-200 bg-transparent px-1 py-1 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                                    </div>
                                </div>
                                @error('no_wa')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                                @error('email')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <label class="pt-2 text-sm font-medium text-gray-600 sm:pt-2">Kualifikasi Pendidikan <span
                            class="text-red-500">*</span></label>
                    <div class="relative pt-2 group sm:pt-0">
                        <span
                            class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                        <input type="text" wire:model="kualifikasi_pendidikan"
                            class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                        @error('kualifikasi_pendidikan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="mt-8 border-t border-gray-50 pt-8">
                <h4 class="mb-6 flex items-center gap-2 font-bold text-gray-800">b. Data Institusi / Perusahaan
                    Sekarang</h4>
                <div
                    class="grid grid-cols-1 items-start gap-x-4 gap-y-5 sm:grid-cols-[140px_1fr] md:grid-cols-[180px_1fr] lg:grid-cols-[200px_1fr] xl:gap-x-6">
                    <label class="text-sm font-medium text-gray-600 sm:pt-2">
                        Nama Institusi / Perusahaan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <span
                            class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                        <input type="text" wire:model="nama_perusahaan"
                            class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                        @error('nama_perusahaan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="text-sm font-medium text-gray-600 sm:pt-2">Jabatan</label>
                    <div class="relative group">
                        <span
                            class="absolute left-[-15px] top-1/2 -translate-y-1/2 text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                        <input type="text" wire:model="jabatan"
                            class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                    </div>

                    <label class="pt-3 text-sm font-medium text-gray-600 sm:pt-2">Alamat Kantor</label>
                    <div class="relative flex flex-col gap-2 pt-3 sm:pt-0 group">
                        <span
                            class="absolute left-[-15px] top-[12px] text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                        <textarea wire:model="alamat_perusahaan" rows="2"
                            class="w-full border-0 border-b-2 border-slate-200 bg-transparent px-0 py-1.5 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors"></textarea>
                        <div class="flex items-center gap-2 self-end">
                            <label class="text-xs text-gray-500">Kode pos <span class="text-red-500">*</span> :</label>
                            <input type="text" wire:model="kode_pos_perusahaan"
                                class="w-24 border-0 border-b-2 border-slate-200 bg-transparent px-1 py-1 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                        </div>
                        @error('kode_pos_perusahaan')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 sm:col-span-2">
                        <div
                            class="grid grid-cols-1 sm:grid-cols-[140px_1fr] md:grid-cols-[180px_1fr] lg:grid-cols-[200px_1fr] xl:gap-x-6 items-start gap-x-4 gap-y-5">
                            <label class="pt-1 text-sm font-medium text-gray-600 sm:pt-2">No. Telp/Fax/E-mail</label>
                            <div class="relative space-y-4 group">
                                <span
                                    class="absolute left-[-15px] top-[12px] text-gray-400 hidden sm:inline group-focus-within:text-emerald-500 transition-colors">:</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">Telp :</label>
                                        <input type="text" wire:model="no_telp_perusahaan"
                                            class="grow border-0 border-b-2 border-slate-200 bg-transparent px-1 py-1 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">Fax :</label>
                                        <input type="text" wire:model="fax_perusahaan"
                                            class="grow border-0 border-b-2 border-slate-200 bg-transparent px-1 py-1 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                                    </div>
                                    <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center gap-2">
                                        <label class="min-w-[50px] text-xs text-gray-500">E-mail :</label>
                                        <input type="email" wire:model="email_perusahaan"
                                            class="grow border-0 border-b-2 border-slate-200 bg-transparent px-1 py-1 text-sm font-medium text-gray-900 focus:border-emerald-500 focus:ring-0 focus:outline-none transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-between gap-4">
            <button type="button" wire:click="previousStep"
                class="inline-flex justify-center items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 w-full sm:w-auto">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </button>
            <button type="button" wire:click="nextStep"
                class="inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800 w-full sm:w-auto">
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
            <p class="mt-1 text-xs italic text-gray-400">Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan
                berikut Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi untuk mendapatkan pengakuan sesuai
                dengan latar belakang pendidikan, pelatihan serta pengalaman kerja yang anda miliki.</p>
        </div>
        <div class="overflow-x-auto rounded-xl border border-gray-300">
            <table class="w-full min-w-[600px] border-collapse">
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
                        <td class="border-r border-b border-gray-300 bg-gray-50 p-3 text-sm font-bold text-gray-700">
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
                                        <input type="radio" wire:model="assessmentPurpose"
                                            value="paling_lambat_pkt"
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
                            @error('assessmentPurpose')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
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

        <div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-between gap-4">
            <button type="button" wire:click="previousStep"
                class="inline-flex justify-center items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 w-full sm:w-auto">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </button>
            <button type="button" wire:click="nextStep"
                class="inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800 w-full sm:w-auto">
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
            <table class="w-full min-w-[700px] table-fixed text-xs">
                <thead class="border-b border-gray-300 bg-gray-50 font-bold">
                    <tr>
                        <td class="w-12 border-r border-gray-300 p-3 text-center">No.</td>
                        <td class="w-72 border-r border-gray-300 p-3">Bukti Persyaratan Dasar</td>
                        <td class="p-3">Upload / Status</td>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @php
                        $supportingDocuments = [
                            [
                                'label' => \App\Models\Registration::apl01RequirementLabels()['ktm_path'],
                                'property' => 'ktm',
                                'accept' => '.jpg,.jpeg,.png',
                                'hint' => 'JPG, JPEG, PNG maksimal 2MB.',
                                'empty' => 'Belum upload',
                            ],
                            [
                                'label' => \App\Models\Registration::apl01RequirementLabels()['khs_path'],
                                'property' => 'khs',
                                'accept' => '.pdf',
                                'hint' => 'PDF maksimal 2MB.',
                                'empty' => 'Belum upload',
                            ],
                            [
                                'label' => \App\Models\Registration::apl01RequirementLabels()[
                                    'internship_certificate_path'
                                ],
                                'property' => 'internshipCertificate',
                                'accept' => '.jpg,.jpeg,.png,.pdf',
                                'hint' => 'JPG, JPEG, PNG, PDF maksimal 2MB.',
                                'empty' => 'Opsional',
                            ],
                            [
                                'label' => \App\Models\Registration::apl01RequirementLabels()['ktp_path'],
                                'property' => 'ktp',
                                'accept' => '.jpg,.jpeg,.png',
                                'hint' => 'JPG, JPEG, PNG maksimal 2MB.',
                                'empty' => 'Belum upload',
                            ],
                            [
                                'label' => \App\Models\Registration::apl01RequirementLabels()['passport_photo_path'],
                                'property' => 'passportPhoto',
                                'accept' => '.jpg,.jpeg,.png',
                                'hint' => 'JPG, JPEG, PNG maksimal 2MB. Preview mengikuti rasio 3x4.',
                                'empty' => 'Belum upload',
                                'photo' => true,
                            ],
                        ];
                    @endphp

                    @foreach ($supportingDocuments as $index => $document)
                        @php
                            $property = $document['property'];
                            $uploadedFile = $this->{$property};
                            $extension = $uploadedFile ? strtolower($uploadedFile->getClientOriginalExtension()) : null;
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true);
                        @endphp
                        <tr wire:key="apl01-document-{{ $property }}">
                            <td class="border-r border-gray-300 p-4 text-center">{{ $index + 1 }}.</td>
                            <td class="border-r border-gray-300 p-4 italic">{{ $document['label'] }}</td>
                            <td class="space-y-3 p-4">
                                @if (($document['photo'] ?? false) === true)
                                    <div wire:ignore.self x-data="{
                                        image: null,
                                        cropper: null,
                                        showModal: false,
                                        init() {
                                            this.$watch('showModal', value => {
                                                if (value) {
                                                    const initLogic = () => {
                                                        setTimeout(() => {
                                                            const img = this.$refs.cropImage;
                                                            const initCropper = () => {
                                                                if (this.cropper) this.cropper.destroy();
                                                                this.cropper = new Cropper(img, {
                                                                    aspectRatio: 3 / 4,
                                                                    viewMode: 1,
                                                                    autoCropArea: 1,
                                                                    dragMode: 'move',
                                                                    background: false,
                                                                    ready: () => {
                                                                        const cropperBox = this.cropper.cropper.querySelector('.cropper-view-box');
                                                                        if (cropperBox && !cropperBox.querySelector('.passport-guide')) {
                                                                            const guide = document.createElement('div');
                                                                            guide.className = 'passport-guide absolute inset-0 pointer-events-none z-10';
                                                                            const svgUrl = `data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 300 400'%3E%3Cg stroke='rgba(0,0,0,0.3)' stroke-width='6'%3E%3Cellipse cx='150' cy='160' rx='80' ry='105' fill='none'/%3E%3Cpath d='M 30 400 C 30 280, 270 280, 270 400' fill='none'/%3E%3C/g%3E%3Cg stroke='white' stroke-width='3' stroke-dasharray='8,8'%3E%3Cellipse cx='150' cy='160' rx='80' ry='105' fill='none'/%3E%3Cpath d='M 30 400 C 30 280, 270 280, 270 400' fill='none'/%3E%3C/g%3E%3C/svg%3E`;
                                                                            guide.style.backgroundImage = `url('${svgUrl}')`;
                                                                            guide.style.backgroundSize = '100% 100%';
                                                                            cropperBox.appendChild(guide);
                                                                        }
                                                                    }
                                                                });
                                                            };
                                    
                                                            if (img.complete && img.naturalWidth > 0) {
                                                                initCropper();
                                                            } else {
                                                                img.onload = initCropper;
                                                            }
                                                        }, 200);
                                                    };
                                    
                                                    if (typeof window.Cropper === 'undefined') {
                                                        const link = document.createElement('link');
                                                        link.rel = 'stylesheet';
                                                        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css';
                                                        document.head.appendChild(link);
                                    
                                                        const script = document.createElement('script');
                                                        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js';
                                                        script.onload = initLogic;
                                                        document.head.appendChild(script);
                                                    } else {
                                                        initLogic();
                                                    }
                                                } else if (this.cropper) {
                                                    this.cropper.destroy();
                                                    this.cropper = null;
                                                }
                                            });
                                        },
                                        onFileChange(e) {
                                            const file = e.target.files[0];
                                            if (file) {
                                                this.image = ''; // Reset image
                                                const reader = new FileReader();
                                                reader.onload = (event) => {
                                                    this.image = event.target.result;
                                                    this.showModal = true;
                                                };
                                                reader.readAsDataURL(file);
                                            }
                                        },
                                        saveCrop() {
                                            const canvas = this.cropper.getCroppedCanvas({
                                                width: 600,
                                                height: 800,
                                            });
                                            const base64 = canvas.toDataURL('image/jpeg');
                                            $wire.set('passportPhotoBase64', base64);
                                            this.showModal = false;
                                        }
                                    }">
                                        <div class="flex flex-col md:flex-row items-start gap-4">
                                            <div
                                                class="relative shrink-0 flex aspect-[3/4] w-28 items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 group">
                                                @if ($passportPhotoBase64)
                                                    <img src="{{ $passportPhotoBase64 }}" alt="Preview pasfoto 3x4"
                                                        class="h-full w-full object-cover">
                                                    <div
                                                        class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
                                                        <button type="button"
                                                            @click="$refs.passportPhotoInput.click()"
                                                            class="rounded-full bg-white p-2 text-zinc-900 shadow-lg">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @elseif ($uploadedFile)
                                                    <img src="{{ $uploadedFile->temporaryUrl() }}"
                                                        alt="Preview pasfoto" class="h-full w-full object-cover">
                                                @else
                                                    <div class="p-4 text-center">
                                                        <svg class="mx-auto mb-2 h-8 w-8 text-slate-300"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <p class="text-[10px] font-medium text-slate-400">Kosong</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div class="relative group max-w-sm">
                                                    <input type="file" @change="onFileChange"
                                                        wire:model="{{ $property }}" x-ref="passportPhotoInput"
                                                        accept="{{ $document['accept'] }}"
                                                        class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0">
                                                    <div @class([
                                                        'flex items-center gap-3 rounded-xl border border-dashed px-3 py-2.5 transition-all',
                                                        'border-emerald-500 bg-emerald-50/50' =>
                                                            $uploadedFile || $passportPhotoBase64,
                                                        'border-slate-300 bg-white group-hover:border-slate-400' =>
                                                            !$uploadedFile && !$passportPhotoBase64,
                                                    ])>
                                                        <div @class([
                                                            'rounded-lg p-1.5',
                                                            'bg-emerald-500 text-white' => $uploadedFile || $passportPhotoBase64,
                                                            'bg-slate-100 text-slate-500' => !$uploadedFile && !$passportPhotoBase64,
                                                        ])>
                                                            <div wire:loading wire:target="{{ $property }}">
                                                                <svg class="h-4 w-4 animate-spin" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div wire:loading.remove
                                                                wire:target="{{ $property }}">
                                                                <svg class="h-4 w-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="text-[11px] font-bold text-gray-900 truncate">
                                                                <span wire:loading
                                                                    wire:target="{{ $property }}">Uploading...</span>
                                                                <span wire:loading.remove
                                                                    wire:target="{{ $property }}">
                                                                    {{ $passportPhotoBase64 ? 'Foto Telah Diatur' : ($uploadedFile ? $uploadedFile->getClientOriginalName() : 'Pilih Pas Foto Formal') }}
                                                                </span>
                                                            </p>
                                                            <p class="mt-0.5 text-[10px] leading-snug text-slate-500">
                                                                JPG, JPEG, PNG maksimal 2MB. Rasio 3:4 akan diterapkan.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if ($passportPhotoBase64 || $uploadedFile)
                                                    <button type="button"
                                                        @click="if (image) showModal = true; else $refs.passportPhotoInput.click()"
                                                        class="mt-2.5 flex items-center gap-1.5 text-xs font-bold text-emerald-600 transition-colors hover:text-emerald-700">
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                        Atur Ulang Crop
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <div x-show="showModal"
                                            class="fixed inset-0 z-[100] flex items-center justify-center bg-zinc-950/80 p-4"
                                            style="display: none;">
                                            <div class="w-full max-w-2xl overflow-hidden rounded-3xl border border-white/10 bg-white shadow-2xl"
                                                wire:ignore>
                                                <div
                                                    class="flex items-center justify-between border-b border-slate-100 p-6">
                                                    <h4 class="text-lg font-bold">Sesuaikan Pas Foto</h4>
                                                    <button type="button" @click="showModal = false"
                                                        class="text-slate-400 transition-colors hover:text-zinc-900">
                                                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="p-6">
                                                    <div id="passport-cropper-container"
                                                        class="max-h-[60vh] overflow-hidden rounded-2xl bg-zinc-100 relative">
                                                        <img x-ref="cropImage" :src="image"
                                                            class="block max-w-full">
                                                    </div>
                                                    <p class="mt-4 text-center text-sm text-slate-500">Geser dan atur
                                                        kotak agar wajah dan bahu sesuai dengan garis bantu putus-putus
                                                        (Rasio 3:4)
                                                        .</p>
                                                </div>
                                                <div class="flex items-center justify-end gap-3 bg-slate-50 p-6">
                                                    <button type="button" @click="showModal = false"
                                                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50">Batal</button>
                                                    <button type="button" @click="saveCrop" :disabled="!cropper"
                                                        :class="!cropper ?
                                                            'opacity-50 cursor-not-allowed bg-slate-300 shadow-none text-slate-500' :
                                                            'bg-emerald-400 hover:bg-emerald-500 shadow-emerald-200 shadow-lg text-black'"
                                                        class="rounded-xl px-8 py-2.5 text-sm font-bold transition-all">Terapkan
                                                        Crop</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="max-w-sm">
                                        <div class="relative group mt-1">
                                            <input type="file" wire:model="{{ $property }}"
                                                accept="{{ $document['accept'] }}"
                                                class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" />
                                            <div @class([
                                                'flex min-h-[44px] items-center gap-2.5 rounded-lg border-2 border-dashed px-3 py-1.5 transition',
                                                'border-gray-200 bg-gray-50/50 group-hover:border-emerald-400 group-hover:bg-emerald-50/30' => !$uploadedFile,
                                                'border-emerald-200 bg-emerald-50/30' => $uploadedFile,
                                            ])>
                                                <div
                                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded bg-white shadow-sm ring-1 ring-gray-200">
                                                    <svg class="h-3.5 w-3.5 text-gray-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 truncate text-left">
                                                    <p class="truncate text-[11px] font-semibold text-gray-700">
                                                        {{ $uploadedFile ? $uploadedFile->getClientOriginalName() : 'Pilih File...' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div wire:loading wire:target="{{ $property }}"
                                        class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-semibold text-amber-700">
                                        Uploading...
                                    </div>

                                    <div wire:loading.remove wire:target="{{ $property }}" class="space-y-2">
                                        @if ($uploadedFile)
                                            <div class="w-fit max-w-full rounded-lg border border-emerald-200 bg-emerald-50/70 p-2">
                                                @if ($isImage)
                                                    <img src="{{ $uploadedFile->temporaryUrl() }}"
                                                        alt="Preview {{ $document['label'] }}"
                                                        class="max-h-32 w-auto max-w-full rounded-md object-contain">
                                                @else
                                                    <div
                                                        class="flex items-center gap-2 rounded-md bg-white px-3 py-2 text-[11px] text-gray-700">
                                                        <span
                                                            class="rounded bg-red-100 px-2 py-1 font-bold text-red-700">PDF</span>
                                                        <span
                                                            class="truncate font-medium">{{ $uploadedFile->getClientOriginalName() }}</span>
                                                    </div>
                                                @endif
                                                <p class="mt-2 truncate text-[10px] font-semibold text-emerald-700">
                                                    {{ $uploadedFile->getClientOriginalName() }}</p>
                                            </div>
                                        @else
                                            <p class="text-[11px] text-gray-500">{{ $document['empty'] }}</p>
                                        @endif
                                        <p class="text-[10px] text-gray-400">{{ $document['hint'] }}</p>
                                    </div>
                                @endif

                                @error($property)
                                    <p class="text-[10px] text-red-500">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-between gap-4">
            <button type="button" wire:click="previousStep"
                class="inline-flex justify-center items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 w-full sm:w-auto">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </button>
            <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                class="inline-flex justify-center items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800 w-full sm:w-auto">
                <span wire:loading.remove wire:target="nextStep">Lanjut ke Bagian 4</span>
                <span wire:loading wire:target="nextStep">Memeriksa...</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    @endif

    @if ($apl01SubStep === 4)
        <div class="mb-6 border-b border-gray-100 pb-4">
            <h3 class="text-xl font-bold italic text-gray-900">Bagian 4 : Rekomendasi & Tanda Tangan</h3>
            <p class="mt-1 text-xs italic text-gray-400">Berikan tanda tangan digital Anda sebagai pernyataan bahwa
                data yang dimasukkan adalah benar.</p>
        </div>

        <div class="overflow-x-auto rounded-xl border border-stone-400 bg-[#fffdf8] shadow-sm">
            <table class="w-full min-w-[600px] table-fixed border-collapse text-sm">
                <tbody>
                    <tr>
                        <td class="w-[52%] border-r border-b border-stone-400 p-4 align-top">
                            <p class="text-[13px] font-bold text-stone-800">Rekomendasi (diisi oleh LSP):</p>
                            <p class="mt-1 text-[13px] leading-6 text-stone-700">Berdasarkan ketentuan persyaratan
                                dasar, maka pemohon:</p>
                            <p class="mt-2 text-[13px] font-semibold text-stone-800">Diterima / Tidak diterima *)
                                sebagai peserta sertifikasi</p>

                        </td>
                        <td class="border-b border-stone-400 p-0 align-top">
                            <table class="w-full table-fixed border-collapse text-sm">
                                <tbody>
                                    <tr>
                                        <td colspan="2"
                                            class="border-b border-stone-400 px-4 py-3 text-[13px] font-bold text-stone-800">
                                            Pemohon / Kandidat :</td>
                                    </tr>
                                    <tr>
                                        <td
                                            class="w-[34%] border-r border-b border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                            Nama</td>
                                        <td class="border-b border-stone-400 px-4 py-3 align-top">
                                            <p class="text-[13px] font-medium text-stone-900">
                                                {{ $name ?: 'Belum diisi' }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            class="border-r border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                            Tanda tangan/
                                            <br>
                                            Tanggal
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <div wire:ignore x-data="{
                                                signaturePad: null,
                                                resizeHandler: null,
                                                currentSignature: @js($applicantSignature),
                                                isDrawing: false,
                                                init() {
                                                    const canvas = this.$refs.signatureCanvas;
                                                    const SignaturePadLib = window.SignaturePad;
                                            
                                                    if (!SignaturePadLib) {
                                                        return;
                                                    }
                                            
                                                    this.resizeHandler = () => {
                                                        if (this.isDrawing) {
                                                            return;
                                                        }
                                            
                                                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                                        const currentValue = this.currentSignature || $wire.applicantSignature;
                                            
                                                        canvas.width = canvas.offsetWidth * ratio;
                                                        canvas.height = canvas.offsetHeight * ratio;
                                                        canvas.getContext('2d').scale(ratio, ratio);
                                            
                                                        if (!this.signaturePad) {
                                                            return;
                                                        }
                                            
                                                        this.signaturePad.clear();
                                            
                                                        if (currentValue) {
                                                            this.signaturePad.fromDataURL(currentValue, { ratio });
                                                        }
                                                    };
                                            
                                                    this.signaturePad = new SignaturePadLib(canvas, {
                                                        backgroundColor: 'rgba(0, 0, 0, 0)',
                                                        penColor: 'rgb(68, 64, 60)',
                                                    });
                                            
                                                    this.resizeHandler();
                                                    window.addEventListener('resize', this.resizeHandler);
                                            
                                                    this.signaturePad.addEventListener('beginStroke', () => {
                                                        this.isDrawing = true;
                                                    });
                                            
                                                    this.signaturePad.addEventListener('endStroke', () => {
                                                        this.isDrawing = false;
                                                        this.currentSignature = this.signaturePad.toDataURL('image/png');
                                                        $wire.set('applicantSignature', this.currentSignature);
                                                    });
                                                },
                                                clearSignature() {
                                                    if (!this.signaturePad) {
                                                        return;
                                                    }
                                            
                                                    this.signaturePad.clear();
                                                    this.currentSignature = null;
                                                    $wire.set('applicantSignature', null);
                                                },
                                                destroy() {
                                                    if (this.resizeHandler) {
                                                        window.removeEventListener('resize', this.resizeHandler);
                                                    }
                                                }
                                            }" x-init="init()">
                                                <div
                                                    class="rounded-lg border border-dashed border-stone-300 bg-stone-50/80 p-2">
                                                    <canvas x-ref="signatureCanvas"
                                                        class="h-28 w-full cursor-crosshair rounded-md bg-transparent"
                                                        style="touch-action: none;"></canvas>
                                                </div>
                                                <div class="mt-2 flex items-center justify-between gap-4">
                                                    <p class="text-[11px] italic text-stone-500">Tanda tangan pakai
                                                        mouse / touchpad / layar sentuh.</p>
                                                    <button type="button" @click="clearSignature()"
                                                        class="text-[11px] font-semibold text-red-600 transition hover:text-red-700">
                                                        Hapus
                                                    </button>
                                                </div>
                                                <p class="mt-2 text-[12px] text-stone-600">
                                                    {{ now()->translatedFormat('d F Y') }}</p>
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
                            <div
                                class="mt-2 min-h-28 rounded-md border border-dashed border-stone-300 bg-white/70 p-3 text-[12px] italic text-stone-500">
                                Menunggu hasil verifikasi admin LSP.
                            </div>
                        </td>
                        <td class="p-0 align-top">
                            <table class="w-full table-fixed border-collapse text-sm">
                                <tbody>
                                    <tr>
                                        <td colspan="2"
                                            class="border-b border-stone-400 px-4 py-3 text-[13px] font-bold text-stone-800">
                                            Admin LSP :</td>
                                    </tr>
                                    <tr>
                                        <td
                                            class="w-[34%] border-r border-b border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                            Nama :</td>
                                        <td class="border-b border-stone-400 px-4 py-3 align-top">
                                            <p
                                                class="text-[13px] {{ $adminVerificationPreview['state'] === 'verified' ? 'font-medium text-stone-900' : 'italic text-stone-400' }}">
                                                {{ $adminVerificationPreview['state'] === 'verified' ? ($adminVerificationPreview['name'] ?: 'Admin LSP') : 'Menunggu verifikasi admin LSP' }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            class="border-r border-stone-400 px-4 py-3 align-top text-[13px] text-stone-700">
                                            Tanda tangan/
                                            <br>
                                            Tanggal
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            @if ($adminVerificationPreview['state'] === 'verified')
                                                <div
                                                    class="rounded-md border border-emerald-200 bg-emerald-50/70 px-3 py-4">
                                                    <p class="text-[13px] font-semibold text-emerald-800">Terverifikasi
                                                        admin LSP</p>
                                                    <p class="mt-1 text-[12px] text-emerald-700">
                                                        {{ $adminVerificationPreview['date'] ?: 'Tanggal verifikasi belum tersedia' }}
                                                    </p>
                                                </div>
                                            @elseif ($adminVerificationPreview['state'] === 'rejected')
                                                <div class="rounded-md border border-red-200 bg-red-50/70 px-3 py-4">
                                                    <p class="text-[13px] font-semibold text-red-700">Verifikasi tidak
                                                        disetujui</p>
                                                    <p class="mt-1 text-[12px] text-red-600">Tanda tangan admin tidak
                                                        ditampilkan.</p>
                                                </div>
                                            @else
                                                <div
                                                    class="rounded-md border border-dashed border-stone-300 bg-stone-50/80 px-3 py-4">
                                                    <p class="text-[13px] italic text-stone-500">Menunggu verifikasi
                                                        admin LSP.</p>
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

        <div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-between gap-4">
            <button type="button" wire:click="previousStep"
                class="inline-flex justify-center items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 w-full sm:w-auto">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </button>
            <button type="button" wire:click="nextStep"
                class="inline-flex justify-center items-center gap-2 rounded-xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-600 w-full sm:w-auto">
                Selesaikan APL 01
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </button>
        </div>
    @endif
</div>
