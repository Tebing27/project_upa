<div class="space-y-6 bg-slate-50/50 p-6 min-h-screen">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.payment') }}" wire:navigate
            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-400 transition-all hover:text-gray-900">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Detail Verifikasi Pembayaran</h1>
            <p class="mt-1 text-sm text-gray-500">Periksa instruksi pembayaran, bukti transfer, dan validasi peserta.</p>
        </div>
    </div>

    <div class="rounded-[1.25rem] bg-white p-6 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
            <div class="space-y-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $registration->user->name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $registration->scheme?->name ?: '-' }}</p>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                            {{ $registration->user->isGeneralUser() ? 'NIK' : 'NIM' }} / Email
                        </p>
                        <p class="mt-1.5 text-sm font-semibold text-gray-900">
                            {{ $registration->user->isGeneralUser() ? ($registration->user->no_ktp ?: '-') : ($registration->user->nim ?: '-') }} / {{ $registration->user->email }}
                        </p>
                    </div>
                    <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Kode Instruksi Pembayaran</p>
                        <p class="mt-1.5 text-sm font-semibold text-gray-900">{{ $registration->payment_reference }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                @if ($registration->payment_proof_path)
                    <a href="{{ Storage::url($registration->payment_proof_path) }}" target="_blank"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50">
                        Lihat Bukti Pembayaran
                    </a>
                @endif
                @if ($registration->payment_proof_path && $registration->status !== 'paid')
                    <button wire:click="verifikasiPembayaran"
                        class="inline-flex items-center justify-center rounded-xl bg-emerald-400 px-5 py-3 text-sm font-bold text-black hover:bg-emerald-500">
                        Verifikasi Pembayaran
                    </button>
                @endif
                @if ($registration->status === 'paid')
                    <button wire:click="lanjutkanKeJadwal"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white hover:bg-blue-700">
                        Lanjut ke Jadwal Ujian
                    </button>
                @endif
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="rounded-2xl border border-gray-100 bg-gray-50/40 p-5">
                <h3 class="text-lg font-bold text-gray-900">Catatan Validasi</h3>
                <p class="mt-2 text-sm text-gray-500">Tolak bukti pembayaran jika nominal, nama peserta, atau dokumen tidak sesuai.</p>
                <textarea wire:model="rejectNote" rows="4"
                    class="mt-4 block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition-all focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                    placeholder="Contoh: Bukti transfer tidak sesuai nominal atau nama rekening pengirim tidak cocok."></textarea>
                @error('rejectNote')
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
                <button wire:click="tolakPembayaran"
                    class="mt-4 inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white hover:bg-red-700">
                    Tolak Pembayaran
                </button>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-5">
                <h3 class="text-lg font-bold text-gray-900">Status Saat Ini</h3>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-xl bg-gray-50/70 px-4 py-3">
                        <span class="text-gray-500">Tahap</span>
                        <span class="font-semibold text-gray-900">Pembayaran</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-gray-50/70 px-4 py-3">
                        <span class="text-gray-500">Status</span>
                        <span class="font-semibold text-gray-900">{{ $registration->statusLabel() }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-gray-50/70 px-4 py-3">
                        <span class="text-gray-500">Upload Bukti</span>
                        <span class="font-semibold text-gray-900">{{ $registration->payment_submitted_at?->translatedFormat('d M Y H:i') ?: 'Belum upload' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-gray-50/70 px-4 py-3">
                        <span class="text-gray-500">Validasi</span>
                        <span class="font-semibold text-gray-900">{{ $registration->payment_verified_at?->translatedFormat('d M Y H:i') ?: '-' }}</span>
                    </div>
                    @if (($registration->document_statuses['payment_proof_path']['note'] ?? null) !== null)
                        <div class="rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $registration->document_statuses['payment_proof_path']['note'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
