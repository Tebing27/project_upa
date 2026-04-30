<div class="rounded-[1.25rem] border border-slate-100 bg-white p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)]">
    <div class="flex flex-col items-center text-center">
        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
            <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <h1 class="mt-4 text-2xl font-bold text-slate-800">Belum Ada Pendaftaran</h1>
        <p class="mt-2 max-w-xl text-sm text-slate-500">
            Halaman status pendaftaran tetap bisa dibuka, tetapi saat ini Anda belum memiliki data
            pendaftaran yang aktif ataupun riwayat pendaftaran terbaru untuk ditampilkan.
        </p>
        <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('dashboard.skema') }}" class="inline-flex items-center rounded-xl bg-emerald-400 px-5 py-3 text-sm font-semibold text-black transition hover:bg-emerald-500">
                Lihat Skema
            </a>
            <a href="{{ route('dashboard.daftar-skema') }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Daftar Skema
            </a>
        </div>
    </div>
</div>
