        <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-8 pb-8 border-b border-gray-50">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $registration->user->name }}</h2>
                        @php
                            $statusColorMap = [
                                'dokumen_ok' => 'bg-teal-50 text-teal-700 border-teal-100',
                                'dokumen_ditolak' => 'bg-red-50 text-red-700 border-red-100',
                                'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'paid' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'terjadwal' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'kompeten' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'tidak_kompeten' => 'bg-red-50 text-red-700 border-red-100',
                            ];
                            $statusClass =
                                $statusColorMap[$registration->status] ?? 'bg-slate-50 text-slate-700 border-slate-100';

                            $statusLabel = str_replace('_', ' ', \Illuminate\Support\Str::title($registration->status));
                            if ($registration->status === 'dokumen_ok') {
                                $statusLabel = 'Terverifikasi';
                            }
                            if (in_array($registration->status, ['pending_payment', 'paid', 'menunggu_verifikasi'])) {
                                $statusLabel = 'Review';
                            }
                        @endphp
                        <span
                            class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-3">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                {{ $registration->user->isGeneralUser() ? 'NIK' : 'NIM' }} / No. Pendaftaran
                            </span>
                            <span class="mt-1.5 text-sm font-semibold text-gray-900">
                                {{ $registration->user->isGeneralUser() ? ($registration->user->no_ktp ?: '-') : ($registration->user->nim ?: '-') }}
                                /
                                {{ $registration->payment_reference ?: '-' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Program Studi /
                                Skema</span>
                            <span
                                class="mt-1.5 text-sm font-semibold text-gray-900 truncate">{{ $registration->user->program_studi }}
                                / {{ optional($registration->scheme)->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 md:mt-0">
                    @if ($this->canProceedToPayment())
                        <button wire:click="lanjutkanKeJadwal"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-400 px-6 py-3 text-sm font-bold text-black shadow-lg shadow-emerald-100 transition-all hover:bg-emerald-500">
                            Lanjut ke Pembayaran
                        </button>
                    @endif
                </div>
            </div>
