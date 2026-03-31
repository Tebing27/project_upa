<div class="min-h-screen bg-[#f8fafc] p-6 md:p-8 font-sans">
    @php
        $steps = [
            1 => 'Daftar',
            2 => 'Verifikasi Data & Dokumen',
            3 => 'Pembayaran',
            4 => 'Ujian Sertifikasi',
            5 => $registration?->status === \App\Models\Registration::STATUS_INCOMPETENT ? 'Tidak Lolos Ujian' : 'Hasil & Sertifikat',
        ];

        $statusBadgeClasses = match ($registration?->status) {
            \App\Models\Registration::STATUS_DOCUMENT_REJECTED, \App\Models\Registration::STATUS_INCOMPETENT => 'bg-red-50 text-red-700 border-red-100',
            \App\Models\Registration::STATUS_SCHEDULED,
            \App\Models\Registration::STATUS_COMPLETED,
            \App\Models\Registration::STATUS_COMPETENT,
            \App\Models\Registration::STATUS_CERTIFICATE_ISSUED => 'bg-[#d1fae5] text-emerald-700 border-[#a7f3d0]/50',
            \App\Models\Registration::STATUS_DOCUMENT_APPROVED, \App\Models\Registration::STATUS_PENDING_VERIFICATION => 'bg-teal-50 text-teal-700 border-teal-100',
            \App\Models\Registration::STATUS_PENDING_PAYMENT, \App\Models\Registration::STATUS_PAID => 'bg-amber-50 text-amber-700 border-amber-100',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };

        $statusBadgeDot = match ($registration?->status) {
            \App\Models\Registration::STATUS_DOCUMENT_REJECTED, \App\Models\Registration::STATUS_INCOMPETENT => 'bg-red-500',
            \App\Models\Registration::STATUS_SCHEDULED, \App\Models\Registration::STATUS_COMPLETED, \App\Models\Registration::STATUS_COMPETENT, \App\Models\Registration::STATUS_CERTIFICATE_ISSUED => 'bg-[#10b981]',
            \App\Models\Registration::STATUS_DOCUMENT_APPROVED, \App\Models\Registration::STATUS_PENDING_VERIFICATION => 'bg-teal-500',
            \App\Models\Registration::STATUS_PENDING_PAYMENT, \App\Models\Registration::STATUS_PAID => 'bg-amber-500',
            default => 'bg-slate-400',
        };

        $statusStyles = [
            'verified' => 'border-[#a7f3d0]/60 bg-[#f0fdf4]',
            'pending' => 'border-amber-100 bg-[#fffbeb]',
            'rejected' => 'border-red-100 bg-[#fef2f2]',
            'missing' => 'border-slate-200 bg-white',
        ];

        $statusTextColors = [
            'verified' => 'text-[#059669]',
            'pending' => 'text-amber-600',
            'rejected' => 'text-red-600',
            'missing' => 'text-slate-500',
        ];

        $statusIconBgs = [
            'verified' => 'bg-[#d1fae5] text-[#059669]',
            'pending' => 'bg-amber-100/70 text-amber-600',
            'rejected' => 'bg-red-100/70 text-red-600',
            'missing' => 'bg-slate-100 text-slate-500',
        ];

        $statusLabels = [
            'verified' => 'Terverifikasi',
            'pending' => 'Menunggu Review',
            'rejected' => 'Ditolak',
            'missing' => 'Belum Upload',
        ];

        $historyColors = [
            'blue' => 'bg-[#3b82f6]',
            'amber' => 'bg-[#f59e0b]',
            'red' => 'bg-[#ef4444]',
            'indigo' => 'bg-[#6366f1]',
            'emerald' => 'bg-[#10b981]',
            'purple' => 'bg-[#8b5cf6]',
        ];

        $historyBgColors = [
            'blue' => 'bg-blue-100/80',
            'amber' => 'bg-amber-100/80',
            'red' => 'bg-red-100/80',
            'indigo' => 'bg-indigo-100/80',
            'emerald' => 'bg-emerald-100/80',
            'purple' => 'bg-purple-100/80',
        ];

        $verifiedDocumentsCount = collect($documentCards)->where('status', 'verified')->count();
    @endphp

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl md:text-[28px] font-bold tracking-tight text-[#1e293b]">Status Pendaftaran</h1>
            <p class="mt-1.5 text-[15px] text-slate-500">Pantau progres pendaftaran, cek status dokumen, dan upload ulang
                dokumen yang ditolak.</p>
        </div>

        @if ($successMessage)
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ $successMessage }}
            </div>
        @endif

        <!-- User Info & Stepper Card -->
        <section
            class="rounded-[1.25rem] border border-slate-100 bg-white p-6 md:p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ auth()->user()->name }}</h2>
                    <p class="mt-1 text-[15px] text-slate-500">
                        {{ $registration?->scheme?->name ?: 'Skema belum dipilih' }} <span class="mx-1.5">&bull;</span>
                        NIM: {{ auth()->user()->nim ?: '-' }}
                    </p>
                </div>

                <div
                    class="inline-flex items-center rounded-full px-4 py-1.5 text-[13px] font-semibold {{ $statusBadgeClasses }}">
                    <span class="mr-2 h-1.5 w-1.5 rounded-full {{ $statusBadgeDot }}"></span>
                    {{ $statusLabel }}
                </div>
            </div>

            <!-- Stepper -->
            <div class="mt-8 mb-4 pb-12">
                <div class="relative flex w-full px-8"> @php
                    $progressWidth = 0;
                    if ($currentStep === 2) {
                        $progressWidth = 25;
                    } elseif ($currentStep === 3) {
                        $progressWidth = 50;
                    } elseif ($currentStep === 4) {
                        $progressWidth = 75;
                    } elseif ($currentStep >= 5) {
                        $progressWidth = 100;
                    }
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
                                        in_array($registration?->status, [\App\Models\Registration::STATUS_DOCUMENT_REJECTED], true)) ||
                                    ($stepNumber === 5 && $registration?->status === \App\Models\Registration::STATUS_INCOMPETENT);

                                $displayLabel = str_replace(' & ', "\n& ", $stepLabel);
                            @endphp

                            <div class="relative flex flex-col items-center shrink-0">
                                <div @class([
                                    'relative z-10 flex shrink-0 h-[40px] w-[40px] items-center justify-center rounded-full text-[15px] font-bold ring-[6px] ring-white transition-colors',
                                    'bg-[#10b981] text-white' =>
                                        $isCompleted || ($isCurrent && !$isRejectedStep),
                                    'bg-red-500 text-white' => $isRejectedStep,
                                    'bg-white border-[2px] border-slate-200 text-slate-400' =>
                                        !$isCompleted && !$isCurrent && !$isRejectedStep,
                                ])>
                                    @if ($isCompleted)
                                        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @elseif ($isRejectedStep)
                                        <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @else
                                        {{ $stepNumber }}
                                    @endif
                                </div>

                                <div
                                    class="absolute top-[52px] left-1/2 -translate-x-1/2 w-[120px] md:w-[150px] text-center">
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

        @if ($registration?->status === \App\Models\Registration::STATUS_PENDING_PAYMENT)
            <div class="rounded-[1.25rem] border border-blue-100 bg-blue-50/50 p-6 md:p-8 shadow-[0_4px_20px_-4px_rgba(37,99,235,0.05)] mt-6 text-center">
                <svg class="w-12 h-12 text-blue-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Instruksi Pembayaran</h3>
                <p class="text-slate-600 mb-6">Silakan lakukan pembayaran Sertifikasi ke Nomor Virtual Account (VA) berikut ini. Setelah membayar, mohon tunggu admin mengkonfirmasi dan memperbarui status pendaftaran Anda menjadi Lunas.</p>
                
                <div class="inline-flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-blue-200 shadow-sm mb-4">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Kode Pembayaran Sertifikasi</span>
                    <span class="font-mono text-3xl font-bold text-blue-600 tracking-wider select-all">{{ $registration->payment_reference }}</span>
                </div>
            </div>
        @endif

        @if (in_array($registration->status, [\App\Models\Registration::STATUS_SCHEDULED, \App\Models\Registration::STATUS_COMPLETED, \App\Models\Registration::STATUS_COMPETENT, \App\Models\Registration::STATUS_INCOMPETENT, \App\Models\Registration::STATUS_CERTIFICATE_ISSUED], true))
            <div class="rounded-[1.25rem] border border-indigo-100 bg-indigo-50/50 p-6 md:p-8 shadow-[0_4px_20px_-4px_rgba(99,102,241,0.05)] mt-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100/80 text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[1.15rem] font-bold text-slate-800">Detail Jadwal Ujian</h3>
                        <p class="text-sm text-slate-500">Informasi pelaksanaan Ujian Sertifikasi Anda.</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-indigo-100/60 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-400">Tanggal Ujian</p>
                        <p class="mt-1.5 font-semibold text-slate-800">{{ $registration->exam_date?->translatedFormat('d F Y') ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-indigo-100/60 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-400">Jam Ujian</p>
                        <p class="mt-1.5 font-semibold text-slate-800">{{ $registration->exam_date?->format('H:i') ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-indigo-100/60 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-400">Lokasi</p>
                        <p class="mt-1.5 font-semibold text-slate-800">{{ $registration->exam_location ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-indigo-100/60 bg-white p-4">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-400">Asesor</p>
                        <p class="mt-1.5 font-semibold text-slate-800">{{ $registration->assessor->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.8fr_1fr]">
            <!-- Dokumen Section -->
            <section
                class="rounded-[1.25rem] border border-slate-100 bg-white p-6 md:p-7 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-[1.15rem] font-bold text-slate-800">Dokumen</h2>
                    <span class="text-[13px] font-medium text-slate-400">{{ count($documentCards) }} dokumen</span>
                </div>

                @if ($documentCards === [])
                    <p class="text-sm text-slate-500">Belum ada data dokumen.</p>
                @else
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($documentCards as $document)
                            <article
                                class="flex flex-col justify-between rounded-xl border p-4 transition-all {{ $statusStyles[$document['status']] ?? $statusStyles['missing'] }}">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $statusIconBgs[$document['status']] ?? $statusIconBgs['missing'] }}">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h7.586A2 2 0 0116 4.586L19.414 8A2 2 0 0120 9.414V18a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="truncate text-[14px] font-semibold text-slate-800">
                                                {{ $document['label'] }}</h3>
                                            <div
                                                class="mt-0.5 flex items-center gap-1.5 {{ $statusTextColors[$document['status']] ?? $statusTextColors['missing'] }}">
                                                @if ($document['status'] === 'verified')
                                                    <svg class="h-[13px] w-[13px]" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @elseif($document['status'] === 'rejected')
                                                    <svg class="h-[13px] w-[13px]" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-[13px] w-[13px]" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                                <span
                                                    class="text-[12px] font-medium">{{ $statusLabels[$document['status']] ?? 'Belum diketahui' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($document['has_file'] && $document['file_url'])
                                        <a href="{{ $document['file_url'] }}" target="_blank"
                                            class="shrink-0 rounded-full border border-[#a7f3d0]/60 bg-white px-3 py-1 text-[12px] font-medium text-[#059669] transition hover:bg-emerald-50">
                                            Lihat File
                                        </a>
                                    @else
                                        <span
                                            class="shrink-0 rounded-full border border-slate-200 bg-white px-3 py-1 text-[12px] font-medium text-slate-400">
                                            Belum Ada File
                                        </span>
                                    @endif
                                </div>

                                @if ($document['note'])
                                    <p class="mt-3 text-[13px] text-slate-600">{{ $document['note'] }}</p>
                                @endif

                                @if ($document['can_reupload'])
                                    <form wire:submit="reuploadDocument('{{ $document['field'] }}')"
                                        class="mt-4 pt-4 border-t border-slate-200/60">
                                        <div class="flex gap-2">
                                            <input type="file" wire:model="reuploadFiles.{{ $document['field'] }}"
                                                class="block w-full text-xs text-slate-500 file:mr-2 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200" />
                                            <button type="submit"
                                                class="shrink-0 rounded-md bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700">
                                                Upload
                                            </button>
                                        </div>
                                        @error('reuploadFiles.' . $document['field'])
                                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </form>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Riwayat Status Section -->
            <section
                class="rounded-[1.25rem] border border-slate-100 bg-white p-6 md:p-7 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
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

                                <div
                                    class="relative z-10 flex h-[1.8rem] w-[1.8rem] shrink-0 items-center justify-center rounded-full {{ $historyBgColors[$history['color']] ?? $historyBgColors['blue'] }}">
                                    <div
                                        class="flex h-[1.15rem] w-[1.15rem] items-center justify-center rounded-full {{ $historyColors[$history['color']] ?? $historyColors['blue'] }} text-white">
                                        <svg class="h-[10px] w-[10px]" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
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
        </div>
    </div>
</div>
