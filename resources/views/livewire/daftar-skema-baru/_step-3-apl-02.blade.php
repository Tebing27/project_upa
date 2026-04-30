<div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] md:p-8">
    <h2 class="text-xl font-bold text-gray-900">APL 02</h2>
    <p class="mt-1 text-sm text-gray-500 italics">Unduh template APL 02 dari admin, lalu upload APL 02 yang sudah diisi. Format PDF maksimal 2MB.</p>

    @if ($selectedScheme?->apl_02_template_path)
        <div class="mt-6 flex flex-col gap-3 rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-900">Template APL 02 dari admin tersedia</p>
                <p class="mt-1 text-xs text-slate-500">Unduh file DOCX ini, lengkapi isinya, lalu unggah kembali versi PDF.</p>
            </div>
            <a href="{{ Storage::url($selectedScheme->apl_02_template_path) }}" download
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-400 px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-emerald-500 md:w-auto">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16" />
                </svg>
                Download APL 02
            </a>
        </div>
    @else
        <div class="mt-6 rounded-2xl border border-amber-100 bg-amber-50/60 p-4 text-sm text-amber-800">
            Template APL 02 belum diupload admin untuk skema ini. Anda tetap bisa mengunggah file APL 02 jika sudah memiliki dokumennya.
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700">FR APL 02 (PDF) *</label>
            <div class="relative mt-2 group">
                <input type="file" wire:model="frApl02" accept=".pdf"
                    class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" />
                <div @class([
                    'flex h-full min-h-[64px] items-center gap-3 rounded-xl border-2 border-dashed px-4 py-3 transition',
                    'border-gray-200 bg-gray-50/50 group-hover:border-emerald-400 group-hover:bg-emerald-50/30' => !$frApl02,
                    'border-emerald-200 bg-emerald-50/30' => $frApl02,
                ])>
                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
                        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1 truncate text-left">
                        <p class="truncate text-[13px] font-semibold text-gray-700">
                            {{ $frApl02 ? $frApl02->getClientOriginalName() : 'Pilih File APL 02...' }}
                        </p>
                        <p class="text-[11px] font-medium text-gray-400">PDF (Maks 2MB)</p>
                    </div>
                </div>
            </div>
            @error('frApl02')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-12 flex flex-col-reverse gap-4 sm:flex-row sm:justify-between">
        <button type="button" wire:click="previousStep"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 sm:w-auto">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </button>
        <button type="button" wire:click="nextStep"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-gray-800 sm:w-auto">
            Selanjutnya
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>
