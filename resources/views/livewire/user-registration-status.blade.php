<div class="space-y-6 p-6">
    @php
        $steps = [
            1 => 'Daftar',
            2 => 'Verifikasi Data & Dokumen',
            3 => 'Jadwal Ujian',
            4 => 'Sertifikat Terbit',
        ];
        $statusBadgeClasses = match ($registration?->status) {
            'dokumen_ditolak', 'rejected', 'tidak_kompeten' => 'bg-red-50 text-red-700 ring-red-200 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-800/70',
            'terjadwal', 'selesai_uji', 'kompeten', 'sertifikat_terbit' => 'bg-blue-50 text-blue-700 ring-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-800/70',
            'dokumen_ok', 'menunggu_verifikasi' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800/70',
            'pending_payment' => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-800/70',
            default => 'bg-zinc-100 text-zinc-700 ring-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:ring-zinc-700',
        };
        $statusStyles = [
            'verified' => 'border-emerald-300 bg-emerald-50 text-emerald-700 dark:border-emerald-800/70 dark:bg-emerald-900/20 dark:text-emerald-300',
            'pending' => 'border-amber-300 bg-amber-50 text-amber-700 dark:border-amber-800/70 dark:bg-amber-900/20 dark:text-amber-300',
            'rejected' => 'border-red-300 bg-red-50 text-red-700 dark:border-red-800/70 dark:bg-red-900/20 dark:text-red-300',
            'missing' => 'border-zinc-300 bg-white text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400',
        ];
        $statusLabels = [
            'verified' => 'Terverifikasi',
            'pending' => 'Menunggu Review',
            'rejected' => 'Ditolak',
            'missing' => 'Belum Upload',
        ];
    @endphp

    <div>
        <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-white">Status Pendaftaran</h1>
        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Pantau progres pendaftaran, cek status dokumen, dan upload ulang dokumen yang ditolak.</p>
    </div>

    @if ($successMessage)
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800/70 dark:bg-emerald-900/20 dark:text-emerald-300">
            {{ $successMessage }}
        </div>
    @endif

    <div class="rounded-[2rem] border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ auth()->user()->name }}</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $registration?->scheme?->name ?: 'Skema belum dipilih' }} • NIM: {{ auth()->user()->nim ?: '-' }}
                </p>
            </div>

            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusBadgeClasses }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="mt-8 overflow-x-auto pb-2">
            <div class="relative mx-auto flex min-w-[700px] justify-between px-1">
                <div class="absolute left-8 right-8 top-5 h-0.5 bg-zinc-200 dark:bg-zinc-700"></div>
                <div class="absolute left-8 top-5 h-0.5 bg-emerald-500 transition-all"
                    style="width: calc((100% - 4rem) * {{ max($currentStep - 1, 0) / 3 }});"></div>

                @foreach ($steps as $stepNumber => $stepLabel)
                    @php
                        $isCompleted = $stepNumber < $currentStep;
                        $isCurrent = $stepNumber === $currentStep;
                        $isRejectedStep = $stepNumber === 2 && in_array($registration?->status, ['dokumen_ditolak', 'rejected'], true);
                    @endphp

                    <div class="relative z-10 flex w-40 flex-col items-center text-center">
                        <div @class([
                            'flex h-10 w-10 items-center justify-center rounded-full border-2 bg-white text-sm font-semibold dark:bg-zinc-900',
                            'border-emerald-500 bg-emerald-500 text-white dark:bg-emerald-500' => $isCompleted,
                            'border-blue-500 text-blue-600 ring-4 ring-blue-100 dark:text-blue-300 dark:ring-blue-900/40' => $isCurrent && ! $isRejectedStep,
                            'border-red-500 text-red-600 ring-4 ring-red-100 dark:text-red-300 dark:ring-red-900/40' => $isRejectedStep,
                            'border-zinc-300 text-zinc-400 dark:border-zinc-600 dark:text-zinc-500' => ! $isCompleted && ! $isCurrent && ! $isRejectedStep,
                        ])>
                            @if ($isCompleted)
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif ($isRejectedStep)
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @else
                                {{ $stepNumber }}
                            @endif
                        </div>

                        <p class="mt-3 text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ $stepLabel }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="rounded-[2rem] border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Dokumen</h2>

        @if ($documentCards === [])
            <p class="mt-4 text-sm text-zinc-500 dark:text-zinc-400">Belum ada data dokumen.</p>
        @else
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ($documentCards as $document)
                    <div class="rounded-2xl border px-4 py-4 {{ $statusStyles[$document['status']] ?? $statusStyles['missing'] }}">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-semibold">{{ $document['label'] }}</h3>
                                <p class="mt-1 text-sm opacity-90">{{ $statusLabels[$document['status']] ?? 'Belum diketahui' }}</p>
                                @if ($document['note'])
                                    <p class="mt-2 text-sm opacity-90">{{ $document['note'] }}</p>
                                @endif
                            </div>

                            <span class="mt-1 inline-flex h-7 items-center rounded-full bg-white/70 px-2 text-xs font-semibold text-current dark:bg-black/10">
                                {{ $document['has_file'] ? 'Ada File' : 'Belum Ada' }}
                            </span>
                        </div>

                        @if ($document['can_reupload'])
                            <form wire:submit="reuploadDocument('{{ $document['field'] }}')" class="mt-4 space-y-3">
                                <input type="file" wire:model="reuploadFiles.{{ $document['field'] }}" class="block w-full rounded-xl border border-current/20 bg-white/80 px-3 py-2 text-sm text-current file:mr-3 file:rounded-lg file:border-0 file:bg-white file:px-3 file:py-2 file:text-sm file:font-semibold file:text-zinc-900 dark:bg-zinc-950/60 dark:file:bg-zinc-800 dark:file:text-white" />
                                @error('reuploadFiles.'.$document['field'])
                                    <p class="text-sm">{{ $message }}</p>
                                @enderror
                                <button type="submit" class="inline-flex items-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                                    Upload Ulang
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="rounded-[2rem] border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Riwayat Status</h2>

        @if ($statusHistory === [])
            <p class="mt-4 text-sm text-zinc-500 dark:text-zinc-400">Belum ada riwayat status.</p>
        @else
            <div class="mt-6 space-y-5">
                @foreach ($statusHistory as $history)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <span @class([
                                'mt-1 h-3 w-3 rounded-full',
                                'bg-blue-500' => $history['color'] === 'blue',
                                'bg-amber-500' => $history['color'] === 'amber',
                                'bg-red-500' => $history['color'] === 'red',
                                'bg-indigo-500' => $history['color'] === 'indigo',
                                'bg-emerald-500' => $history['color'] === 'emerald',
                            ])></span>
                            @if (! $loop->last)
                                <span class="mt-2 h-full w-px bg-zinc-200 dark:bg-zinc-700"></span>
                            @endif
                        </div>
                        <div class="pb-4">
                            <p class="font-semibold text-zinc-900 dark:text-white">{{ $history['title'] }}</p>
                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">{{ $history['description'] }}</p>
                            @if ($history['date'])
                                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ $history['date'] }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
