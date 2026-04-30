<section class="rounded-[1.25rem] border border-slate-100 bg-white p-6 md:p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h2>
            <p class="mt-1 text-[15px] text-slate-500">
                {{ $registration?->scheme?->name ?: 'Skema belum dipilih' }} <span class="mx-1.5">&bull;</span>
                {{ $identityLabel }}:
                {{ $identityValue }}
            </p>
        </div>

        <div
            class="inline-flex w-fit justify-center items-center rounded-full px-4 py-2 sm:py-1.5 text-xs font-semibold {{ $statusBadgeClasses }}">
            <span class="mr-2 h-1.5 w-1.5 rounded-full {{ $statusBadgeDot }}"></span>
            {{ $statusLabel }}
        </div>
    </div>

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

            <div class="absolute left-0 top-[19px] h-[2px] bg-[#10b981] transition-all duration-500 z-0"
                style="width: {{ $progressWidth }}%;"></div>

            <div class="relative flex w-full justify-between z-10">
                @foreach ($steps as $stepNumber => $stepLabel)
                    @php
                        $isCompleted = $stepNumber < $currentStep;
                        $isCurrent = $stepNumber === $currentStep;
                        $isRejectedStep =
                            ($stepNumber === 2 &&
                                in_array($registration?->status, ['dokumen_ditolak', 'rejected'], true)) ||
                            ($stepNumber === 5 && $registration?->status === 'tidak_kompeten');

                        $displayLabel = str_replace(' & ', "\n& ", $stepLabel);
                    @endphp

                    <div @if ($isCurrent) id="active-step-item" @endif
                        class="relative flex flex-col items-center shrink-0">
                        <div @class([
                            'relative z-10 flex shrink-0 h-[40px] w-[40px] items-center justify-center rounded-full text-[15px] font-bold ring-[6px] ring-white transition-colors',
                            'bg-[#10b981] text-white' =>
                                $isCompleted || ($isCurrent && !$isRejectedStep),
                            'bg-red-500 text-white' => $isRejectedStep,
                            'bg-white border-[2px] border-slate-200 text-slate-400' =>
                                !$isCompleted && !$isCurrent && !$isRejectedStep,
                        ])>
                            @if ($isCompleted)
                                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif ($isRejectedStep)
                                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @else
                                {{ $stepNumber }}
                            @endif
                        </div>

                        <div class="absolute top-[52px] left-1/2 -translate-x-1/2 w-[120px] md:w-[150px] text-center">
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
</section>
