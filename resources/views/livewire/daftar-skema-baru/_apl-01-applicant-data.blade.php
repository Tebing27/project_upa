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
