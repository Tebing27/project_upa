    @if ($pendingCompetencyRegistrations->isNotEmpty())
        <div class="rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="border-b border-gray-100 px-6 py-5 md:px-8">
                <h2 class="text-[1.15rem] font-bold text-gray-900">Status Kompeten Menunggu Sertifikat Copy</h2>
                <p class="mt-1 text-sm text-gray-500">Surat keterangan sudah bisa diunduh lebih dulu. Sertifikat copy akan muncul setelah admin mengunggahnya.</p>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach ($pendingCompetencyRegistrations as $registration)
                    <div class="flex flex-col gap-4 px-6 py-5 md:px-8 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $registration->scheme?->nama ?? '-' }}</p>
                            <p class="mt-1 text-[13px] text-gray-500">
                                Diputuskan kompeten
                                @if ($registration->updated_at)
                                    pada {{ $registration->updated_at->translatedFormat('d F Y') }}
                                @endif
                            </p>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row w-full lg:w-auto">
                            @if ($registration->exam?->exam_result_path)
                                <a href="{{ Storage::url($registration->exam->exam_result_path) }}" target="_blank"
                                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-emerald-400 px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-emerald-500">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16" />
                                    </svg>
                                    Unduh Surat Keterangan
                                </a>
                            @elseif ($hasCompetencyLetterAssets)
                                <button wire:click="downloadCompetencyLetter({{ $registration->id }})"
                                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-emerald-400 px-4 py-2.5 text-sm font-semibold text-black transition hover:bg-emerald-500">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16" />
                                    </svg>
                                    <span wire:loading.remove wire:target="downloadCompetencyLetter({{ $registration->id }})">Unduh Surat Keterangan</span>
                                    <span wire:loading wire:target="downloadCompetencyLetter({{ $registration->id }})">Memproses...</span>
                                </button>
                            @else
                                <button disabled
                                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-400">
                                    Surat keterangan belum tersedia
                                </button>
                            @endif

                            <button type="button" x-on:click="$dispatch('certificate-missing')"
                                class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[13px] sm:text-sm font-semibold text-slate-600 text-center transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700">
                                Unduh Sertifikat
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
