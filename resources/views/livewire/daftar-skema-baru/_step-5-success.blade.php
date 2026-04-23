<div class="rounded-[1.25rem] bg-white p-8 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)]">
    <div class="flex flex-col items-center py-6 text-center">
        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
            <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h2 class="mt-4 text-2xl font-bold text-emerald-700">Pendaftaran Berhasil!</h2>
        <p class="mt-2 max-w-md text-sm text-gray-500 italics">Pendaftaran berhasil dikirim. Tahap
            berikutnya adalah verifikasi data dan dokumen oleh admin.</p>
        @if ($submittedRegistration)
            <a href="{{ route('dashboard.status', $submittedRegistration) }}"
                class="mt-4 inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 transition-all hover:bg-emerald-100">
                Lihat Status Pendaftaran
            </a>
        @endif
        <a href="{{ route('dashboard') }}"
            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-gray-800">
            Kembali ke Dashboard
        </a>
    </div>
</div>
