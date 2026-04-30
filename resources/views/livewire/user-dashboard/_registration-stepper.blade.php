<div id="stepper-container" class="mt-8 mb-4 pb-24 overflow-x-auto overflow-y-hidden" style="scrollbar-width: none;">
    <div class="relative flex w-full min-w-[500px] px-16 md:px-20">
        @php
            $progressWidth = 0;
            $progressWidth = match ($currentStep) {
                2 => 25,
                3 => 50,
                4 => 75,
                5 => 100,
                default => 0,
            };
        @endphp

        <div class="absolute left-0 right-0 top-[19px] h-[2px] bg-slate-200 z-0"></div>

        <div class="absolute left-0 top-[19px] h-[2px] bg-[#10b981] z-0" style="width: {{ $progressWidth }}%;"></div>

        <div class="relative z-10 flex w-full justify-between">
            @foreach ($steps as $stepNumber => $stepLabel)
                @php
                    $isCompleted = $stepNumber < $currentStep;
                    $isCurrent = $stepNumber === $currentStep;
                    $isRejectedStep =
                        ($stepNumber === 2 &&
                            in_array($latestRegistration?->status, ['dokumen_ditolak', 'rejected'], true)) ||
                        ($stepNumber === 5 && $latestRegistration?->status === 'tidak_kompeten');

                    $displayLabel = str_replace(' & ', "\n& ", $stepLabel);
                @endphp

                <div @if ($isCurrent) id="active-step-item" @endif class="relative flex shrink-0 flex-col items-center">
                    <div @class([
                        'relative z-10 flex shrink-0 h-[40px] w-[40px] items-center justify-center rounded-full text-[15px] font-bold ring-[6px] ring-white transition-colors',
                        'bg-[#10b981] text-white' => $isCompleted || ($isCurrent && !$isRejectedStep),
                        'bg-red-500 text-white' => $isRejectedStep,
                        'bg-white border-[2px] border-slate-200 text-slate-400' => !$isCompleted && !$isCurrent && !$isRejectedStep,
                    ])>
                        @if ($isCompleted)
                            <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        @elseif ($isRejectedStep)
                            <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        @else
                            {{ $stepNumber }}
                        @endif
                    </div>

                    <div class="absolute top-[52px] left-1/2 w-[120px] -translate-x-1/2 text-center md:w-[150px]">
                        <p @class([
                            'whitespace-pre-line leading-[1.3] text-[11px] md:text-[13px]',
                            'text-slate-600 font-medium' => $isCompleted || $isCurrent,
                            'text-slate-400' => !$isCompleted && !$isCurrent,
                            'text-red-600 font-medium' => $isRejectedStep,
                        ])>
                            {{ $displayLabel }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
