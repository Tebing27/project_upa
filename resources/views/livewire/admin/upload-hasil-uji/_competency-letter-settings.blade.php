    <div class="rounded-[1.25rem] border border-emerald-100 bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
            <div class="max-w-2xl">
                <h2 class="text-lg font-bold text-gray-900">Surat Keterangan Kompeten</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Nama penandatangan, tanda tangan, dan stempel ini dipakai untuk surat keterangan yang bisa diunduh peserta saat statusnya sudah kompeten tetapi sertifikat copy belum diunggah admin.
                </p>
            </div>

            <div class="w-full max-w-3xl space-y-4">
                @if ($competencyLetterSignaturePath || $competencyLetterStampPath || $competencyLetterSignatoryName)
                    <div class="grid gap-4 rounded-2xl border border-emerald-100 bg-emerald-50/40 p-4 md:grid-cols-2">
                        <div class="rounded-xl bg-white p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Penandatangan</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $competencyLetterSignatoryName ?: '-' }}</p>

                            @if ($competencyLetterSignaturePath)
                                <img src="{{ Storage::url($competencyLetterSignaturePath) }}" alt="Tanda tangan surat keterangan"
                                    class="mt-4 h-28 w-full rounded-xl bg-slate-50 p-3 object-contain">
                            @endif
                        </div>

                        <div class="rounded-xl bg-white p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Stempel</p>

                            @if ($competencyLetterStampPath)
                                <img src="{{ Storage::url($competencyLetterStampPath) }}" alt="Stempel surat keterangan"
                                    class="mt-4 h-28 w-full rounded-xl bg-slate-50 p-3 object-contain">
                            @else
                                <p class="mt-2 text-sm text-slate-500">Belum ada stempel.</p>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="md:col-span-3">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Penandatangan</label>
                        <input type="text" wire:model="competencyLetterSignatoryName"
                            class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">
                        @error('competencyLetterSignatoryName')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Tanda Tangan</label>
                        <input type="file" wire:model="competencyLetterSignatureFile"
                            class="block w-full text-xs text-slate-500 file:mr-2 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                        @error('competencyLetterSignatureFile')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Stempel</label>
                        <input type="file" wire:model="competencyLetterStampFile"
                            class="block w-full text-xs text-slate-500 file:mr-2 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                        @error('competencyLetterStampFile')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-end">
                        <div class="flex w-full flex-wrap gap-3">
                            <button wire:click="saveCompetencyLetterSettings" type="button"
                                class="inline-flex flex-1 items-center justify-center rounded-xl bg-emerald-400 px-5 py-3 text-sm font-bold text-black hover:bg-emerald-500">
                                Simpan
                            </button>
                            @if ($competencyLetterSignaturePath || $competencyLetterStampPath || $competencyLetterSignatoryName)
                                <button wire:click="deleteCompetencyLetterSettings" type="button"
                                    class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-white px-5 py-3 text-sm font-bold text-red-600 hover:bg-red-50">
                                    Hapus
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

