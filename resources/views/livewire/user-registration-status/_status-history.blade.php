<section class="rounded-[1.25rem] border border-slate-100 bg-white p-6 md:p-7 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
    <h2 class="text-[1.15rem] font-bold text-slate-800 mb-6">Riwayat Status</h2>

    @if ($statusHistory === [])
        <p class="text-sm text-slate-500">Belum ada riwayat status.</p>
    @else
        <div class="space-y-6">
            @foreach ($statusHistory as $index => $history)
                <div class="relative flex gap-4">
                    @if (!$loop->last)
                        <div class="absolute left-[0.9rem] top-8 -bottom-6 w-[2px] bg-slate-100"></div>
                    @endif

                    <div class="relative z-10 flex h-[1.8rem] w-[1.8rem] shrink-0 items-center justify-center rounded-full {{ $historyBgColors[$history['color']] ?? $historyBgColors['blue'] }}">
                        <div class="flex h-[1.15rem] w-[1.15rem] items-center justify-center rounded-full {{ $historyColors[$history['color']] ?? $historyColors['blue'] }} text-white">
                            <svg class="h-[10px] w-[10px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <div class="pb-2">
                        <p class="text-[14.5px] font-semibold text-slate-800 leading-tight">
                            {{ $history['title'] }}</p>
                        <p class="mt-1 text-[13px] text-slate-500 leading-snug">
                            {{ $history['description'] }}</p>
                        @if ($history['date'])
                            <p class="mt-1 text-[12px] font-medium text-slate-400">{{ $history['date'] }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
