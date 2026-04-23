<div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
    <h2 class="text-xl font-bold text-gray-900">APL 02</h2>
    <p class="mt-1 text-sm text-gray-500 italics">Upload APL 02 yang diperlukan untuk pendaftaran
        sertifikasi. Format PDF maksimal 2MB.</p>

    @if ($selectedScheme?->apl_02_template_path)
        <div class="mt-6 flex flex-col gap-3 rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-900">Template APL 02 dari admin tersedia</p>
                <p class="mt-1 text-xs text-slate-500">Unduh file DOCX ini sebagai format acuan sebelum Anda mengunggah FR APL 02 versi PDF.</p>
            </div>
            <a href="{{ Storage::url($selectedScheme->apl_02_template_path) }}" download
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-400 px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-emerald-500">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16" />
                </svg>
                Download Template APL 02
            </a>
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">FR APL 01 (PDF) *</label>
            <input type="file" wire:model="frApl01" accept=".pdf"
                class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700">
            @error('frApl01')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">FR APL 02 (PDF) *</label>
            <input type="file" wire:model="frApl02" accept=".pdf"
                class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700">
            @error('frApl02')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-12 flex justify-between">
        <button type="button" wire:click="previousStep"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </button>
        <button type="button" wire:click="nextStep"
            class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800">
            Selanjutnya
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>
