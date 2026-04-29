<div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
    <h2 class="text-xl font-bold text-gray-900">Review Pendaftaran</h2>
    <p class="mt-1 text-sm text-gray-500">Pastikan biodata dan dokumen sudah sesuai sebelum
        mengirim pendaftaran ke tahap verifikasi.</p>

    <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50/50 p-6">
        <h3 class="text-lg font-semibold text-gray-900">Ringkasan Data</h3>
        <div class="mt-4 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
            <div><span class="font-semibold text-gray-900">Nama:</span> <span class="text-gray-600">{{ $name ?: '-' }}</span></div>
            <div><span class="font-semibold text-gray-900">{{ auth()->user()->isGeneralUser() ? 'NIK' : 'NIM' }}:</span>
                <span class="text-gray-600">{{ auth()->user()->isGeneralUser() ? ($no_ktp ?: '-') : ($nim ?: '-') }}</span>
            </div>
            <div><span class="font-semibold text-gray-900">No. WhatsApp:</span> <span class="text-gray-600">{{ $no_wa ?: ($telp_rumah ?: '-') }}</span></div>
            <div><span class="font-semibold text-gray-900">{{ auth()->user()->isGeneralUser() ? 'Nama Institusi / Perusahaan' : 'Program Studi' }}:</span>
                <span class="text-gray-600">{{ auth()->user()->isGeneralUser() ? ($nama_perusahaan ?: '-') : ($program_studi ?: '-') }}</span>
            </div>
            <div><span class="font-semibold text-gray-900">Tipe Pendaftaran:</span> <span class="text-gray-600">Skema Baru</span>
            </div>
            <div class="sm:col-span-2"><span class="font-semibold text-gray-900">Skema Sertifikasi:</span> <span
                    class="text-gray-600">{{ $selectedScheme?->name ?? '-' }}</span></div>
        </div>
    </div>

    <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50/50 p-6">
        <h3 class="text-lg font-semibold text-emerald-900">Tahap Berikutnya</h3>
        <p class="mt-2 text-sm italic text-emerald-800">Setelah pendaftaran dikirim, admin akan
            memverifikasi
            data dan dokumen Anda. Jika lolos verifikasi, Anda akan masuk ke tahap pembayaran.</p>
    </div>

    <div class="mt-12 flex flex-col-reverse gap-4 sm:flex-row sm:justify-between">
        <button type="button" wire:click="previousStep"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 sm:w-auto">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </button>
        <button type="button" wire:click="submit" wire:loading.attr="disabled"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
            Kirim Pendaftaran
        </button>
    </div>
</div>
