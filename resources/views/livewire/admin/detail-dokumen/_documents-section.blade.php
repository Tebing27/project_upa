            <h3 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wider text-gray-400 mb-6">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Dokumen Pendaftaran
            </h3>

            @php
                $verifiableDocs = $registration->reviewableDocumentFields();
                $docs = collect($registration->visibleDocumentFields())
                    ->mapWithKeys(function (string $field): array {
                        return [
                            $field => match ($field) {
                                'fr_apl_01_path' => 'Formulir APL-01',
                                'fr_apl_02_path' => 'Formulir APL-02',
                                'ktm_path' => 'KTM',
                                'khs_path' => 'KHS / Transkrip',
                                'internship_certificate_path' => 'Sertifikat Magang',
                                'ktp_path' => 'KTP',
                                'passport_photo_path' => 'Pas Foto 3x4',
                                default => \Illuminate\Support\Str::title(str_replace('_', ' ', $field)),
                            },
                        ];
                    })
                    ->all();
                $statuses = $registration->document_statuses ?? [];
            @endphp

            @if ($registration->usesSimplifiedDocumentFlow())
                <div class="mb-6 rounded-2xl border border-blue-100 bg-blue-50/70 p-4 text-sm text-blue-800">
                    Peserta ini menggunakan alur pendaftaran skema baru lanjutan. Dokumen yang direview hanya FR APL 02.
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($docs as $docField => $docName)
                    @php
                        $docData = $statuses[$docField] ?? null;
                        $hasFile = !empty($registration->$docField);
                        $canBeVerified = in_array($docField, $verifiableDocs, true);
                        $docStatus = $canBeVerified
                            ? ($docData['status'] ?? 'pending')
                            : ($hasFile ? 'supporting' : 'missing');
                    @endphp

                    <div
                        class="flex flex-col rounded-2xl border bg-white p-6 transition-all {{ $hasFile ? 'border-gray-100 shadow-sm' : 'border-dashed border-gray-100 bg-gray-50/30' }}">
                        <div class="flex justify-between items-start mb-5">
                            <div class="font-bold text-sm text-gray-900 leading-tight w-2/3">{{ $docName }}
                            </div>
                            @if ($hasFile)
                                @if ($docStatus === 'verified')
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                @elseif($docStatus === 'rejected')
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-red-50 text-red-600">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </span>
                                @elseif($docStatus === 'supporting')
                                    <span
                                        class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                                        Pendukung
                                    </span>
                                @else
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                @endif
                            @else
                                <span class="text-xs font-semibold text-gray-400">Kosong</span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            @if ($hasFile)
                                <div class="flex items-center gap-2">
                                    <a href="{{ Storage::url($registration->$docField) }}" target="_blank"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl border border-gray-200 bg-white text-slate-900 hover:bg-gray-50 py-3 text-xs font-semibold">
                                        Lihat
                                    </a>

                                    @if ($canBeVerified && $docStatus !== 'verified')
                                        <button wire:click="verifikasiDokumen('{{ $docField }}')"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-400 py-3 text-xs font-semibold text-black shadow-sm transition-all hover:bg-emerald-500">
                                            Verifikasi
                                        </button>
                                    @endif

                                    @if ($canBeVerified && $docStatus !== 'rejected')
                                        <button wire:click="bukaModalTolak('{{ $docField }}')"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-red-600 py-3 text-xs font-semibold text-white shadow-sm transition-all hover:bg-red-700">
                                            Tolak
                                        </button>
                                    @endif
                                </div>
                                @if (! $canBeVerified)
                                    <div class="mt-3 rounded-xl bg-slate-50 p-3 text-xs font-medium text-slate-500">
                                        Dokumen pendukung ini ikut ditampilkan, tetapi tidak memerlukan verifikasi admin.
                                    </div>
                                @endif
                                @if ($docStatus === 'rejected' && !empty($docData['note']))
                                    <div class="mt-3 rounded-xl bg-red-50 p-3">
                                        <p class="text-xs leading-relaxed text-red-700 font-medium">
                                            <span class="font-bold">Catatan:</span> {{ $docData['note'] }}
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="py-2 text-center text-xs font-medium text-gray-400 italic">Peserta belum
                                    mengunggah dokumen</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
