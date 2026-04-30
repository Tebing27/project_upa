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
