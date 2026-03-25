<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Verifikasi Dokumen</h1>
        <p class="mt-1 text-sm text-gray-500">Tinjau dan verifikasi kelengkapan dokumen pendaftaran mahasiswa.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center">
        {{-- Segmented Control (Tabs) --}}
        <div class="flex p-1.5 gap-1.5 border border-gray-100 bg-gray-100/50 rounded-2xl shrink-0">
            <button wire:click="setTab('perlu_review')"
                class="px-5 py-2 text-sm font-semibold rounded-xl transition-all {{ $tab === 'perlu_review' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Perlu
                Review</button>
            <button wire:click="setTab('dokumen_ok')"
                class="px-5 py-2 text-sm font-semibold rounded-xl transition-all {{ $tab === 'dokumen_ok' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Dokumen
                Lengkap</button>
            <button wire:click="setTab('ditolak')"
                class="px-5 py-2 text-sm font-semibold rounded-xl transition-all {{ $tab === 'ditolak' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Ditolak</button>
        </div>

        <div class="flex flex-wrap gap-3 w-full lg:w-auto">
            <div class="relative flex-1 sm:w-64">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari Nama / NIM..."
                    class="block w-full px-4 py-3 pl-10 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
            </div>

            <div class="flex-1 sm:w-48">
                <select wire:model.live="filterScheme"
                    class="block w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 outline-none transition-all hover:bg-slate-50/50 hover:border-slate-300 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white">
                    <option value="">Semua Skema</option>
                    @foreach ($schemes as $scheme)
                        <option value="{{ $scheme->id }}">{{ $scheme->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($registrations as $reg)
            <div
                class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] transition-all hover:shadow-[0_4px_15px_-4px_rgba(0,0,0,0.08)]">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="text-xl font-bold text-gray-900">{{ $reg->user->name }}</h2>
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
                                    $statusColorMap[$reg->status] ?? 'bg-slate-50 text-slate-700 border-slate-100';

                                $statusLabel = str_replace('_', ' ', Str::title($reg->status));
                                if ($reg->status === 'dokumen_ok') {
                                    $statusLabel = 'Draft OK';
                                }
                                if (in_array($reg->status, ['pending_payment', 'paid', 'menunggu_verifikasi'])) {
                                    $statusLabel = 'Review';
                                }
                            @endphp
                            <span
                                class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rounded-xl border border-gray-50 bg-gray-50/30 p-4 flex flex-col">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">NIM / No.
                                    Pendaftaran</span>
                                <span class="mt-1.5 text-sm font-semibold text-gray-900">{{ $reg->user->nim ?? '-' }} /
                                    {{ $reg->payment_reference ?? '-' }}</span>
                            </div>
                            <div class="rounded-xl border border-gray-50 bg-gray-50/30 p-4 flex flex-col">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">Program Studi
                                    / Skema</span>
                                <span
                                    class="mt-1.5 text-sm font-semibold text-gray-900 truncate">{{ $reg->user->program_studi ?? '-' }}
                                    / {{ optional($reg->scheme)->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row md:flex-col gap-2 shrink-0">
                        <a href="{{ route('admin.verifikasi.detail', $reg) }}" wire:navigate
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50">
                            Lihat Detail
                        </a>
                        @if ($reg->status === 'dokumen_ok')
                            <button wire:click="lanjutkanKeJadwal({{ $reg->id }})"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-bold text-black hover:bg-emerald-500">
                                Beri Jadwal
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="py-20 text-center rounded-[1.25rem] bg-white border-2 border-dashed border-gray-100">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 text-gray-400 mx-auto">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-bold text-gray-900">Tidak ada data</h3>
                <p class="mt-1 text-sm text-gray-500">Belum ada permohonan yang sesuai dengan filter.</p>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $registrations->links() }}
        </div>
    </div>
</div>
