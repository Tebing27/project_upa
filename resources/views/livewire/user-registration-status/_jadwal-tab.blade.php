<div class="mt-6 rounded-[1.25rem] border border-blue-100 bg-gradient-to-r from-blue-50 via-white to-emerald-50 p-5 md:p-6">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Jadwal Ujian
            </p>
            <h3 class="mt-2 text-[1.15rem] font-bold text-slate-800">
                {{ $registration->scheme?->name ?: 'Skema Sertifikasi' }}
            </h3>
            <p class="mt-2 max-w-2xl text-sm text-slate-600">
                Jadwal Anda sudah diterbitkan admin. Simpan detail berikut dan gunakan link WhatsApp
                untuk koordinasi lebih lanjut.
            </p>
        </div>

        @if ($globalWhatsappLink)
            <a href="{{ $globalWhatsappLink }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-xl bg-emerald-400 px-5 py-3 text-sm font-semibold text-black transition hover:bg-emerald-500">
                Buka Link WhatsApp
            </a>
        @endif
    </div>

    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl border border-white/70 bg-white/80 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Tanggal &
                Waktu</p>
            <p class="mt-2 text-sm font-semibold text-slate-800">
                {{ $registration->exam_date?->translatedFormat('d M Y, H:i') ?? '-' }} WIB
            </p>
        </article>
        <article class="rounded-2xl border border-white/70 bg-white/80 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Lokasi</p>
            <p class="mt-2 text-sm font-semibold text-slate-800">
                {{ $registration->exam_location ?: '-' }}</p>
        </article>
        <article class="rounded-2xl border border-white/70 bg-white/80 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Asesor</p>
            <p class="mt-2 text-sm font-semibold text-slate-800">
                {{ $registration->assessor_name ?: '-' }}</p>
        </article>
        <article class="rounded-2xl border border-white/70 bg-white/80 p-4">
            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Link
                WhatsApp</p>
            @if ($globalWhatsappLink)
                <a href="{{ $globalWhatsappLink }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                    Buka Sekarang
                </a>
            @else
                <p class="mt-2 text-sm font-semibold text-slate-800">-</p>
            @endif
        </article>
    </div>
</div>
