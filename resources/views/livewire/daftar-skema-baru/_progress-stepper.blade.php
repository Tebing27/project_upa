<div class="mb-8" x-data="{ confirming: false }">
    <div class="relative flex w-full px-2 md:px-8">
        <div class="absolute left-0 right-0 top-[19px] z-0 h-[2px] bg-slate-200"></div>
        <div class="absolute left-0 top-[19px] z-0 h-[2px] bg-emerald-500 transition-all duration-500"
            style="width: {{ $progressWidth }}%;"></div>

        <div class="relative z-10 flex w-full justify-between">
            @foreach ($steps as $stepKey => $stepLabel)
                @php
                    $isCompleted = $stepKey < $currentStep;
                    $isCurrent = $stepKey === $currentStep;
                @endphp
                <div class="relative flex shrink-0 flex-col items-center">
                    <div @class([
                        'relative z-10 flex h-[40px] w-[40px] items-center justify-center rounded-full text-[15px] font-bold ring-[6px] ring-white transition-colors',
                        'bg-emerald-500 text-white' => $isCompleted || $isCurrent,
                        'border-[2px] border-slate-200 bg-white text-slate-400' => ! $isCompleted && ! $isCurrent,
                    ])>
                        @if ($isCompleted)
                            <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            {{ $loop->iteration }}
                        @endif
                    </div>
                    <div class="absolute left-1/2 top-[52px] w-[120px] -translate-x-1/2 text-center md:w-[150px]">
                        <p
                            class="text-[11px] leading-[1.3] md:text-[13px] {{ $isCompleted || $isCurrent ? 'font-medium text-slate-600' : 'text-slate-400' }}">
                            {{ $stepLabel }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
