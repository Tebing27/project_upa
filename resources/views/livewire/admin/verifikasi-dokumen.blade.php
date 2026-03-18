<div class="p-6">
    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white mb-6">Verifikasi Dokumen</h1>

    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
        <!-- Segmented Control (Tabs) -->
        <div class="flex p-1 space-x-1 border border-zinc-200 bg-zinc-100 dark:bg-zinc-800/50 dark:border-zinc-700/50 rounded-lg shrink-0">
            <button wire:click="setTab('perlu_review')" class="w-full sm:w-auto px-3 py-1.5 text-sm font-medium rounded-md transition-all {{ $tab === 'perlu_review' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-600' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300' }}">Perlu Review</button>
            <button wire:click="setTab('dokumen_ok')" class="w-full sm:w-auto px-3 py-1.5 text-sm font-medium rounded-md transition-all {{ $tab === 'dokumen_ok' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-600' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300' }}">Dokumen Lengkap</button>
            <button wire:click="setTab('ditolak')" class="w-full sm:w-auto px-3 py-1.5 text-sm font-medium rounded-md transition-all {{ $tab === 'ditolak' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-600' : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300' }}">Ditolak</button>
        </div>

        <div class="flex gap-4 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / NIM..." class="block w-full rounded-md border-0 py-1.5 pl-10 text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 dark:bg-zinc-800 dark:text-white placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
            </div>
            
            <div class="w-full sm:w-48">
                <select wire:model.live="filterScheme" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 dark:ring-zinc-700 dark:bg-zinc-800 dark:text-white focus:ring-2 focus:ring-zinc-900 dark:focus:ring-white sm:text-sm sm:leading-6">
                    <option value="">Semua Skema</option>
                    @foreach($schemes as $scheme)
                        <option value="{{ $scheme->id }}">{{ $scheme->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($registrations as $reg)
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ $reg->user->name }}</h2>
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
                                $statusClass = $statusColorMap[$reg->status] ?? 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300';
                                
                                $statusLabel = str_replace('_', ' ', Str::title($reg->status));
                                if ($reg->status === 'dokumen_ok') $statusLabel = 'Dokumen OK';
                            @endphp
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ring-black/10 dark:ring-white/10 {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                            <div><span class="font-medium text-zinc-900 dark:text-zinc-300">NIM:</span> {{ $reg->user->nim }}</div>
                            <div><span class="font-medium text-zinc-900 dark:text-zinc-300">No. Pendaftaran:</span> {{ $reg->payment_reference ?? '-' }}</div>
                            <div><span class="font-medium text-zinc-900 dark:text-zinc-300">Program Studi:</span> {{ $reg->user->program_studi }}</div>
                            <div><span class="font-medium text-zinc-900 dark:text-zinc-300">Skema:</span> {{ optional($reg->scheme)->name }}</div>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 flex flex-col gap-2">
                    <a href="{{ route('admin.verifikasi.detail', $reg) }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:hover:bg-zinc-700 transition-colors">
                        Lihat Detail
                    </a>
                    @if($reg->status === 'dokumen_ok')
                        <button wire:click="lanjutkanKeJadwal({{ $reg->id }})" class="inline-flex items-center justify-center gap-2 rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-900 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition-colors">
                            Lanjutkan ke Jadwal
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
            <div class="py-12 text-center border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl">
                <svg class="w-12 h-12 mx-auto text-zinc-400 dark:text-zinc-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Tidak ada data</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Belum ada permohonan yang sesuai dengan filter.</p>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $registrations->links() }}
        </div>
    </div>
</div>
