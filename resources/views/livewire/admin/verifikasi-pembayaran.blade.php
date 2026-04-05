<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Verifikasi Pembayaran</h1>
        <p class="mt-1 text-sm text-gray-500">Validasi bukti pembayaran sebelum peserta bisa dijadwalkan ujian.</p>
    </div>

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex rounded-2xl border border-gray-100 bg-gray-100/50 p-1.5">
            @foreach (['perlu_review' => 'Perlu Review', 'terverifikasi' => 'Terverifikasi', 'ditolak' => 'Ditolak'] as $key => $label)
                <button wire:click="setTab('{{ $key }}')"
                    class="rounded-xl px-5 py-2 text-sm font-semibold transition-all {{ $tab === $key ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="flex w-full flex-wrap gap-3 lg:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama / NIM / NIK / email..."
                class="block flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition-all focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 lg:w-72">
            <select wire:model.live="filterScheme"
                class="block rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition-all focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 lg:w-56">
                <option value="">Semua Skema</option>
                @foreach ($schemes as $scheme)
                    <option value="{{ $scheme->id }}">{{ $scheme->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="space-y-4">
        @forelse ($registrations as $registration)
            @php
                $paymentProofStatus = $registration->paymentProofStatus();
                $statusLabel = $registration->statusLabel();
            @endphp
            <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
                <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-xl font-bold text-gray-900">{{ $registration->user->name }}</h2>
                            <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                {{ $statusLabel }}
                            </span>
                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $paymentProofStatus === 'verified' ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : ($paymentProofStatus === 'rejected' ? 'border-red-100 bg-red-50 text-red-700' : 'border-amber-100 bg-amber-50 text-amber-700') }}">
                                {{ $paymentProofStatus === 'verified' ? 'Bukti valid' : ($paymentProofStatus === 'rejected' ? 'Bukti ditolak' : ($registration->payment_proof_path ? 'Menunggu validasi' : 'Belum upload bukti')) }}
                            </span>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Peserta</p>
                                <p class="mt-1.5 text-sm font-semibold text-gray-900">
                                    {{ $registration->user->isGeneralUser() ? ($registration->user->no_ktp ?: $registration->user->email) : ($registration->user->nim ?: $registration->user->email) }}
                                </p>
                            </div>
                            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Skema / Kode Pembayaran</p>
                                <p class="mt-1.5 text-sm font-semibold text-gray-900">{{ $registration->scheme?->name ?: '-' }} / {{ $registration->payment_reference }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex shrink-0 flex-row gap-2 md:flex-col">
                        <a href="{{ route('admin.payment.detail', $registration) }}" wire:navigate
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50">
                            Lihat Detail
                        </a>
                        @if ($registration->status === 'paid')
                            <button wire:click="lanjutkanKeJadwal({{ $registration->id }})"
                                class="inline-flex items-center justify-center rounded-xl bg-emerald-400 px-5 py-2.5 text-sm font-bold text-black hover:bg-emerald-500">
                                Beri Jadwal
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-[1.25rem] bg-white py-20 text-center text-sm text-gray-500">Belum ada data pembayaran untuk filter ini.</div>
        @endforelse

        {{ $registrations->links() }}
    </div>
</div>
