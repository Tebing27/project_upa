<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.verifikasi') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Detail Verifikasi Dokumen</h1>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-6 pb-6 border-b border-zinc-200 dark:border-zinc-700">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ $registration->user->name }}</h2>
                        @php
                            $statusColorMap = [
                                'dokumen_ok' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'dokumen_ditolak' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                'menunggu_verifikasi' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'pending_payment' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'paid' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'terjadwal' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                'kompeten' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                'tidak_kompeten' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $statusClass = $statusColorMap[$registration->status] ?? 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300';
                            
                            $statusLabel = str_replace('_', ' ', \Illuminate\Support\Str::title($registration->status));
                            if ($registration->status === 'dokumen_ok') $statusLabel = 'Dokumen OK';
                        @endphp
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ring-black/10 dark:ring-white/10 {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                        <div><span class="font-medium text-zinc-900 dark:text-zinc-300">NIM:</span> {{ $registration->user->nim }}</div>
                        <div><span class="font-medium text-zinc-900 dark:text-zinc-300">No. Pendaftaran:</span> {{ $registration->payment_reference ?? '-' }}</div>
                        <div><span class="font-medium text-zinc-900 dark:text-zinc-300">Program Studi:</span> {{ $registration->user->program_studi }}</div>
                        <div><span class="font-medium text-zinc-900 dark:text-zinc-300">Skema:</span> {{ optional($registration->scheme)->name }}</div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    @if($registration->status === 'dokumen_ok')
                        <button wire:click="lanjutkanKeJadwal" class="inline-flex items-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                            Lanjutkan ke Jadwal
                        </button>
                    @endif
                </div>
            </div>

            @php
                $docs = [
                    'fr_apl_01_path' => 'Formulir APL-01',
                    'fr_apl_02_path' => 'Formulir APL-02',
                    'ktm_path' => 'KTM',
                    'khs_path' => 'KHS / Transkrip',
                    'internship_certificate_path' => 'Sertifikat Magang',
                    'ktp_path' => 'KTP',
                    'passport_photo_path' => 'Pas Foto 3x4',
                    'payment_reference' => 'Bukti UKT / Pembayaran'
                ];
                $statuses = $registration->document_statuses ?? [];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($docs as $docField => $docName)
                    @php
                        $docData = $statuses[$docField] ?? null;
                        $docStatus = $docData['status'] ?? 'pending';
                        $hasFile = !empty($registration->$docField);
                    @endphp
                    
                    <div class="p-4 border rounded-xl {{ $hasFile ? 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800' : 'border-dashed border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50' }}">
                        <div class="flex justify-between items-start mb-3">
                            <div class="font-medium text-sm text-zinc-900 dark:text-zinc-100">{{ $docName }}</div>
                            @if($hasFile)
                                @if($docStatus === 'verified')
                                    <span class="inline-flex items-center gap-1 rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/30 dark:text-green-400 dark:ring-green-900/50">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Verified
                                    </span>
                                @elseif($docStatus === 'rejected')
                                    <span class="inline-flex items-center gap-1 rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/30 dark:text-red-400 dark:ring-red-900/50">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-400 dark:ring-amber-900/50">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Pending
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center rounded-md bg-zinc-100 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10 dark:bg-zinc-800 dark:text-zinc-400 dark:ring-zinc-700">Belum Unggah</span>
                            @endif
                        </div>
                        
                        @if($hasFile)
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ Storage::url($registration->$docField) }}" target="_blank" class="inline-flex items-center gap-1 rounded px-2 py-1 text-xs font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-700 ring-1 ring-inset ring-zinc-200 dark:ring-zinc-700 bg-white dark:bg-zinc-800 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Lihat
                                </a>
                                
                                @if($docStatus !== 'verified')
                                    <button wire:click="verifikasiDokumen('{{ $docField }}')" class="inline-flex items-center gap-1 rounded bg-zinc-900 px-2 py-1 text-xs font-medium text-white shadow-sm hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Verifikasi
                                    </button>
                                @endif
                                
                                @if($docStatus !== 'rejected')
                                    <button wire:click="bukaModalTolak('{{ $docField }}')" class="inline-flex items-center gap-1 rounded bg-red-600 px-2 py-1 text-xs font-medium text-white shadow-sm hover:bg-red-500 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Tolak
                                    </button>
                                @endif
                            </div>
                            @if($docStatus === 'rejected' && !empty($docData['note']))
                                <div class="mt-2 text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-2 rounded">
                                    <span class="font-semibold">Catatan:</span> {{ $docData['note'] }}
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div x-data="{ show: false }" 
         x-on:open-modal.window="if ($event.detail.id === 'modal-tolak') show = true" 
         x-on:close-modal.window="if ($event.detail.id === 'modal-tolak') show = false" 
         x-on:keydown.escape.window="show = false" 
         x-show="show" 
         class="relative z-50" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true" 
         style="display: none;">
         
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-zinc-500/75 dark:bg-black/75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="show" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative overflow-hidden w-full rounded-xl bg-white dark:bg-zinc-900 text-left shadow-xl transition-all sm:my-8 sm:max-w-md pt-6">
                    <form wire:submit="tolakDokumen">
                        <div class="px-6 pb-6 space-y-6 flex-1">
                            <div>
                                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white" id="modal-title">Tolak Dokumen</h2>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Berikan catatan mengapa dokumen ini ditolak.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium leading-6 text-zinc-900 dark:text-white mb-2">Catatan Penolakan</label>
                                <textarea wire:model="rejectNote" rows="3" placeholder="Misal: Dokumen buram, tidak dapat terbaca dengan jelas." class="block w-full rounded-md border-0 py-1.5 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 dark:bg-zinc-800 dark:text-white placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6" required></textarea>
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-3 bg-zinc-50 dark:bg-zinc-800/50">
                            <button type="button" @click="show = false" class="rounded-md bg-white dark:bg-zinc-800 px-3 py-2 text-sm font-semibold text-zinc-900 dark:text-white shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700">Batal</button>
                            <button type="submit" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Tolak Dokumen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
